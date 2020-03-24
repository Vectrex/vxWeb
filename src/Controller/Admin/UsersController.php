<?php

namespace App\Controller\Admin;

use vxPHP\Http\ParameterBag;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Application\Application;
use vxPHP\Util\Rex;
use vxPHP\Http\JsonResponse;
use vxPHP\Webpage\MenuGenerator;
use vxPHP\Constraint\Validator\RegularExpression;
use vxPHP\Constraint\Validator\Email;
use vxPHP\Security\Password\PasswordEncrypter;

use vxWeb\User\Util;

class UsersController extends Controller {

	protected function execute(): Response
    {
		return new Response(SimpleTemplate::create('admin/users_list.php')->display());
	}

    protected function del (): JsonResponse
    {
        if(!($id = $this->request->query->getInt('id'))) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        try {
            if(Application::getInstance()->getCurrentUser()->getAttribute('adminid') === $id) {
                return new JsonResponse(null, Response::HTTP_FORBIDDEN);
            }
            Application::getInstance()->getDb()->deleteRecord('admin', $id);
        }
        catch(ArticleException $e) {
            return new JsonResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['success' => true]);
    }

    protected function edit (): Response
    {
        if(!($id = $this->request->query->getInt('id'))) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        // editing own record is not allowed

        if(Application::getInstance()->getCurrentUser()->getAttribute('adminid') === $id) {
            return new Response('', Response::HTTP_FORBIDDEN);
        }

        $db = Application::getInstance()->getDb();
        $userRow = $db->doPreparedQuery(sprintf("SELECT adminid AS id, username, name FROM %s WHERE adminid = ?", $db->quoteIdentifier('admin')), [$id])->current();

        if(!$userRow) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        MenuGenerator::setForceActiveMenu(true);
        return new Response(SimpleTemplate::create('admin/users_edit.php')->assign('user', $userRow)->display());
    }

    protected function add ()
    {
        MenuGenerator::setForceActiveMenu(true);
        return new Response(SimpleTemplate::create('admin/users_edit.php')->assign('user', [])->display());
    }

    protected function editInit (): JsonResponse
    {
        $db = Application::getInstance()->getVxPDO();

        if ($id = $this->request->query->getInt('id')) {
            $formData = $db->doPreparedQuery("SELECT adminid as id, username, email, name, admingroupsid FROM " . $db->quoteIdentifier('admin') . " WHERE adminid = ?", [$id])->current();
        }

        return new JsonResponse([
            'form' => $formData ?? null,
            'options' => [
                'admingroups' => (array) $db->doPreparedQuery("SELECT admingroupsid, name FROM admingroups ORDER BY privilege_level")
            ]
        ]);
    }

    protected function init (): JsonResponse
    {
        $app = Application::getInstance();
        $admin = $app->getCurrentUser();
        $db = $app->getDb();

        $users = $db->doPreparedQuery("SELECT a.*, ag.alias, a.adminid AS `key` FROM " . $db->quoteIdentifier('admin') . " a LEFT JOIN admingroups ag ON ag.admingroupsID = a.admingroupsID", []);

        return new JsonResponse(['users' => (array) $users, 'currentUser' => ['username' => $admin->getUsername()]]);
    }

    protected function update (): JsonResponse
    {
        $request = new ParameterBag(json_decode($this->request->getContent(), true));
        $id = $request->get('id');

        $db = Application::getInstance()->getVxPDO();

        $form = HtmlForm::create()
            ->addElement(FormElementFactory::create('input', 'username', null, [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Benutzername ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create('input', 'email', null, [], [], true, ['trim', 'lowercase'], [new Email()], 'Ungültige E-Mail Adresse.'))
            ->addElement(FormElementFactory::create('input', 'name', null, [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Name ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create('password', 'new_PWD', null, [], [], !$request->get('id'), [], [new RegularExpression('/^[^\s].{4,}[^\s]$/')], 'Das Passwort muss mindestens 4 Zeichen umfassen.'))
            ->addElement(FormElementFactory::create('password', 'new_PWD_verify', null))
            ->addElement(FormElementFactory::create('select', 'admingroupsid', null, [], [], true, [], [new RegularExpression(Rex::INT_EXCL_NULL)], 'Eine Benutzergruppe muss zugewiesen werden.'))
        ;

        $v = $form
            ->disableCsrfToken()
            ->bindRequestParameters($request)
            ->validate()
            ->getValidFormValues()
        ;

        $errors = $form->getFormErrors();

        if(!isset($errors['new_PWD'])) {

            if(!empty($v['new_PWD'])) {
                if($v['new_PWD'] !== $v['new_PWD_verify']) {
                    $form->setError('new_PWD_verify', null, 'Passwörter stimmen nicht überein.');
                }
                else {
                    $v['pwd'] = (new PasswordEncrypter())->hashPassword($v['new_PWD']);
                }
            }
        }

        if($id) {
            $userRow = $db->doPreparedQuery("SELECT * FROM " . $db->quoteIdentifier('admin') . " WHERE adminid = ?", [$id])->current();

            if (!$userRow) {
                return new JsonResponse('', Response::HTTP_FORBIDDEN);
            }
        }

        if(!isset($errors['email']) && (!$id || $v['email'] != strtolower($userRow['email'])) && !Util::isAvailableEmail($v['email'])) {
            $form->setError('email', null, 'Email wird bereits verwendet.');
        }
        if(!isset($errors['username']) && (!$id || $v['username'] !== $userRow['username']) && !Util::isAvailableUsername($v['username'])) {
            $form->setError('username', null, 'Username wird bereits verwendet.');
        }

        if(!($errors = $form->getFormErrors())) {

            try {

                if($id) {
                    $db->updateRecord('admin', $id, $v->all());
                } else {
                    $id = $db->insertRecord('admin', $v->all());
                }

                return new JsonResponse([
                    'success' => true,
                    'instanceId' => $id,
                    'message' => 'Daten erfolgreich übernommen.'
                ]);

            } catch (\Exception $e) {
                return new JsonResponse([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }

        }

        $response = [];

        foreach($errors as $element => $error) {
            $response[$element] = $error->getErrorMessage();
        }

        return new JsonResponse(['success' => false, 'errors' => $response, 'message' => 'Formulardaten unvollständig oder fehlerhaft.']);
    }
}
