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
     */
    protected function init(): JsonResponse
    {
        $app = Application::getInstance();
        $admin = $app->getCurrentUser();
        $pdo = $app->getVxPDO();

        $users = (array) $pdo->doPreparedQuery(sprintf("
                SELECT
                    a.adminid AS %s,
                    a.adminid AS %s,
                    a.name,
                    a.username,
                    a.email,
                    a.misc_data,
                    ag.admingroupsid,
                    ag.alias
                FROM
                    %s a LEFT JOIN admingroups ag ON ag.admingroupsid = a.admingroupsid
            ",
            $pdo->quoteIdentifier('id'),
            $pdo->quoteIdentifier('key'),
            $pdo->quoteIdentifier('admin')
        ));
        array_walk($users, callback: static function(&$user) {
           if ($user['misc_data']) {
               $user['misc'] = json_decode($user['misc_data']);
           }
           unset($user['misc_data']);
        });
        return new JsonResponse(['users' => $users, 'currentUser' => ['username' => $admin->getUsername()]]);
    }

    /**
     * @return JsonResponse
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
     * @throws \Throwable
     */
    protected function editInit(): JsonResponse
    {
        $pdo = Application::getInstance()->getVxPDO();

        if ($id = (int)$this->route->getPathParameter('id')) {
            $formData = $pdo->doPreparedQuery("SELECT adminid as id, username, email, name, admingroupsid, misc_data FROM " . $pdo->quoteIdentifier('admin') . " WHERE adminid = ?", [$id])->current();

            if (!$formData) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }
            if ($formData['misc_data']) {
                $formData['misc'] = json_decode($formData['misc_data'], false, 512, JSON_THROW_ON_ERROR);
                unset($formData['misc_data']);
            }
        }

        return new JsonResponse([
            'form' => $formData ?? null,
            'options' => [
                'admingroupsid' => (array)$pdo->doPreparedQuery("SELECT admingroupsid AS " . $pdo->quoteIdentifier('key') . ", name AS label FROM admingroups ORDER BY privilege_level")
            ]
        ]);
    }

    /**
     * @return JsonResponse
     * @throws \Throwable
     */
    protected function update(): JsonResponse
    {
        $request = new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        $id = $request->get('id');

        $pdo = Application::getInstance()->getVxPDO();

        $form = HtmlForm::create()
            ->addElement(FormElementFactory::create(type: 'input', name: 'username', required: true, modifiers: ['trim'], validators: [new RegularExpression(Rex::NOT_EMPTY_TEXT)], validationErrorMessage: 'Der Benutzername ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create(type: 'input', name: 'email', required: true, modifiers: ['trim', 'lowercase'], validators: [new Email()], validationErrorMessage: 'Ungültige E-Mail Adresse.'))
            ->addElement(FormElementFactory::create(type: 'input', name: 'name', required: true, modifiers: ['trim'], validators: [new RegularExpression(Rex::NOT_EMPTY_TEXT)], validationErrorMessage: 'Der Name ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create(type: 'password', name: 'new_PWD', required: !$request->get('id'), validators: [new RegularExpression('/^\S.{4,}\S$/')], validationErrorMessage: 'Das Passwort muss mindestens 6 Zeichen umfassen.'))
            ->addElement(FormElementFactory::create(type: 'password', name: 'new_PWD_verify'))
            ->addElement(FormElementFactory::create(type: 'select', name: 'admingroupsid', required: true, validators: [new RegularExpression(Rex::INT_EXCL_NULL)], validationErrorMessage: 'Eine Benutzergruppe muss zugewiesen werden.'));

        $v = $form
            ->disableCsrfToken()
            ->bindRequestParameters($request)
            ->validate()
            ->getValidFormValues()
        ;

        $errors = $form->getFormErrors();

        if (!isset($errors['new_PWD']) && !empty($v['new_PWD'])) {
            if ($v['new_PWD'] !== $v['new_PWD_verify']) {
                $form->setError('new_PWD_verify', null, 'Passwörter stimmen nicht überein.');
            } else {
                $v['pwd'] = (new PasswordEncrypter())->hashPassword($v['new_PWD']);
            }
        }

        if ($id) {
            $userRow = $pdo->doPreparedQuery("SELECT * FROM " . $pdo->quoteIdentifier('admin') . " WHERE adminid = ?", [$id])->current();

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

            // stringify misc data

            $v->set('misc_data', json_encode($request->get('misc', new \stdClass()), JSON_THROW_ON_ERROR));

            try {
                if ($id) {
                    $pdo->updateRecord('admin', $id, $v->all());
                } else {
                    $id = $pdo->insertRecord('admin', $v->all());
                }

                $user = $pdo->doPreparedQuery(sprintf("
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
                    $pdo->quoteIdentifier('id'),
                    $pdo->quoteIdentifier('key'),
                    $pdo->quoteIdentifier('admin')
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