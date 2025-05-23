<?php

namespace App\Controller\Admin;

use vxPHP\Application\Application;
use vxPHP\Application\Exception\ApplicationException;
use vxPHP\Application\Exception\ConfigException;
use vxPHP\Constraint\Validator\Email;
use vxPHP\Constraint\Validator\RegularExpression;
use vxPHP\Controller\Controller;
use vxPHP\Form\Exception\FormElementFactoryException;
use vxPHP\Form\Exception\HtmlFormException;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\HtmlForm;
use vxPHP\Http\JsonResponse;
use vxPHP\Http\ParameterBag;
use vxPHP\Http\Response;
use vxPHP\Security\Csrf\Exception\CsrfTokenException;
use vxPHP\Security\Password\PasswordEncrypter;
use vxPHP\Util\Rex;
use vxWeb\User\Util;

class UsersController extends Controller
{
    /**
     * @return JsonResponse
     * @throws \Throwable
     * @throws ApplicationException
     * @throws ConfigException
     */
    protected function init(): JsonResponse
    {
        $app = Application::getInstance();
        $admin = $app->getCurrentUser();
        $db = $app->getVxPDO();

        $users = $db->doPreparedQuery(sprintf("
                SELECT
                    a.adminid AS %s,
                    a.adminid AS %s,
                    a.name,
                    a.username,
                    a.email,
                    ag.admingroupsid,
                    ag.alias
                FROM
                    %s a LEFT JOIN admingroups ag ON ag.admingroupsid = a.admingroupsid
            ",
            $db->quoteIdentifier('id'),
            $db->quoteIdentifier('key'),
            $db->quoteIdentifier('admin')
        ));

        return new JsonResponse(['users' => (array)$users, 'currentUser' => ['username' => $admin->getUsername()]]);
    }

    /**
     * @return JsonResponse
     * @throws ApplicationException
     * @throws ConfigException
     * @throws \Throwable
     */
    protected function del(): JsonResponse
    {
        $id = (int)$this->route->getPathParameter('id');

        if (Application::getInstance()->getCurrentUser()->getAttribute('adminid') === $id) {
            return new JsonResponse(null, Response::HTTP_FORBIDDEN);
        }
        Application::getInstance()->getVxPDO()->deleteRecord('admin', $id);

        return new JsonResponse(['success' => true, 'id' => $id]);
    }

    /**
     * @return JsonResponse
     * @throws ApplicationException
     * @throws ConfigException
     * @throws \Throwable
     */
    protected function editInit(): JsonResponse
    {
        $db = Application::getInstance()->getVxPDO();

        if ($id = (int)$this->route->getPathParameter('id')) {
            $formData = $db->doPreparedQuery("SELECT adminid as id, username, email, name, admingroupsid FROM " . $db->quoteIdentifier('admin') . " WHERE adminid = ?", [$id])->current();
        }

        return new JsonResponse([
            'form' => $formData ?? null,
            'options' => [
                'admingroupsid' => (array)$db->doPreparedQuery("SELECT admingroupsid AS " . $db->quoteIdentifier('key') . ", name AS label FROM admingroups ORDER BY privilege_level")
            ]
        ]);
    }

    /**
     * @return JsonResponse
     * @throws ApplicationException
     * @throws ConfigException
     * @throws \JsonException
     * @throws \Throwable
     * @throws FormElementFactoryException
     * @throws HtmlFormException
     * @throws CsrfTokenException
     */
    protected function update(): JsonResponse
    {
        $request = new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        $id = $request->get('id');

        $db = Application::getInstance()->getVxPDO();

        $form = HtmlForm::create()
            ->addElement(FormElementFactory::create('input', 'username', null, [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Benutzername ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create('input', 'email', null, [], [], true, ['trim', 'lowercase'], [new Email()], 'Ungültige E-Mail Adresse.'))
            ->addElement(FormElementFactory::create('input', 'name', null, [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Name ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create('password', 'new_PWD', null, [], [], !$request->get('id'), [], [new RegularExpression('/^\S.{4,}\S$/')], 'Das Passwort muss mindestens 6 Zeichen umfassen.'))
            ->addElement(FormElementFactory::create('password', 'new_PWD_verify'))
            ->addElement(FormElementFactory::create('select', 'admingroupsid', null, [], [], true, [], [new RegularExpression(Rex::INT_EXCL_NULL)], 'Eine Benutzergruppe muss zugewiesen werden.'));

        $v = $form
            ->disableCsrfToken()
            ->bindRequestParameters($request)
            ->validate()
            ->getValidFormValues();

        $errors = $form->getFormErrors();

        if (!isset($errors['new_PWD']) && !empty($v['new_PWD'])) {
            if ($v['new_PWD'] !== $v['new_PWD_verify']) {
                $form->setError('new_PWD_verify', null, 'Passwörter stimmen nicht überein.');
            } else {
                $v['pwd'] = (new PasswordEncrypter())->hashPassword($v['new_PWD']);
            }
        }

        if ($id) {
            $userRow = $db->doPreparedQuery("SELECT * FROM " . $db->quoteIdentifier('admin') . " WHERE adminid = ?", [$id])->current();

            if (!$userRow) {
                return new JsonResponse('', Response::HTTP_FORBIDDEN);
            }
        }

        if (!isset($errors['email']) && (!$id || $v['email'] !== strtolower($userRow['email'])) && !Util::isAvailableEmail($v['email'])) {
            $form->setError('email', null, 'Email wird bereits verwendet.');
        }
        if (!isset($errors['username']) && (!$id || $v['username'] !== $userRow['username']) && !Util::isAvailableUsername($v['username'])) {
            $form->setError('username', null, 'Username wird bereits verwendet.');
        }

        if (!$form->getFormErrors()) {
            try {
                if ($id) {
                    $db->updateRecord('admin', $id, $v->all());
                } else {
                    $id = $db->insertRecord('admin', $v->all());
                }

                $user = $db->doPreparedQuery(sprintf("
                SELECT
                    a.adminid AS %s,
                    a.adminid AS %s,
                    a.name,
                    a.username,
                    a.email,
                    ag.admingroupsid,
                    ag.alias
                FROM
                    %s a LEFT JOIN admingroups ag ON ag.admingroupsid = a.admingroupsid
                WHERE
                    a.adminid = ?
            ",
                    $db->quoteIdentifier('id'),
                    $db->quoteIdentifier('key'),
                    $db->quoteIdentifier('admin')
                ), [$id])->current();

                return new JsonResponse([
                    'success' => true,
                    'form' => (array)$user,
                    'message' => 'Daten erfolgreich übernommen.'
                ]);

            } catch (\Exception $e) {
                return new JsonResponse([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }

        }

        return new JsonResponse([
            'success' => false,
            'errors' => array_map(static fn($error) => $error->getErrorMessage(), $form->getFormErrors()),
            'message' => 'Formulardaten unvollständig oder fehlerhaft.'
        ]);
    }
}