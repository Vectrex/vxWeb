<?php

namespace App\Controller\Admin;

use vxPHP\Http\ParameterBag;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Application\Application;
use vxPHP\User\User;
use vxPHP\Util\Rex;
use vxPHP\Http\JsonResponse;
use vxPHP\Routing\Router;
use vxPHP\Webpage\MenuGenerator;
use vxPHP\Constraint\Validator\RegularExpression;
use vxPHP\Constraint\Validator\Email;
use vxPHP\Security\Password\PasswordEncrypter;

use vxWeb\User\Util;

class UsersController extends Controller {

	protected function execute() {

		$app = Application::getInstance();
		$admin = $app->getCurrentUser();
		$db = $app->getDb();
		
		$connection = $app->getDb()->getConnection();
        $redirectUrl = $app->getRouter()->getRoute('users')->getUrl();
		$action = $this->route->getPathParameter('action');

		// editing or deleting something? Ensure user exists

		if(($id = urldecode($this->request->query->get('id')))) {

			// editing own record is not allowed
			
			if($admin->getUsername() == $id) {
				return $this->redirect($redirectUrl);
			}

			$userRows = $db->doPreparedQuery(sprintf("SELECT * FROM %s WHERE username = ?", $db->quoteIdentifier('admin')), [$id]);

			if(!count($userRows)) {
				return $this->redirect($redirectUrl);
			}

			$userRow = $userRows[0];

		}
		
		// delete user
		
		if($id && $action === 'del') {
		
			$db->deleteRecord('admin', ['username' => $id]);

			return $this->redirect($redirectUrl);
			
		}

		// edit or add user
		
		if($id || $action === 'new') {

			MenuGenerator::setForceActiveMenu(TRUE);

			$form = HtmlForm::create('admin_edit_user.htm')->setAttribute('class', 'editUserForm');
			
			if($id) {

				$form->setInitFormValues($userRow);
				$submitLabel = 'Änderungen übernehmen';
			
			}
			
			else {
			
				$submitLabel = 'User anlegen';
				$form->initVar('is_add', 1);
			}

			$admingroups = $connection->query('SELECT admingroupsID, name FROM admingroups ORDER BY privilege_level')->fetchAll(\PDO::FETCH_KEY_PAIR);

			$form
				->addElement(FormElementFactory::create('button', 'submit_user', '', ['type' => 'submit'])->setInnerHTML($submitLabel))
				->addElement(FormElementFactory::create('input',	'username'))
				->addElement(FormElementFactory::create('input',	'email'))
				->addElement(FormElementFactory::create('input',	'name'))
				->addElement(FormElementFactory::create('password',	'new_PWD'))
				->addElement(FormElementFactory::create('password',	'new_PWD_verify'))
				->addElement(FormElementFactory::create('select',	'admingroupsid', NULL, [], $admingroups));
			
			$form->bindRequestParameters();
			
			return new Response(
				SimpleTemplate::create('admin/users_edit.php')
					->assign('user', isset($userRow) ? $userRow : NULL)
					->assign('form', $form->render())
					->display()
			);
		}
		
		$users	= $db->doPreparedQuery("SELECT a.*, ag.alias FROM " . $db->quoteIdentifier('admin') . " a LEFT JOIN admingroups ag ON ag.admingroupsID = a.admingroupsID", []);

		return new Response(
			SimpleTemplate::create('admin/users_list.php')
				->assign('users', $users)
				->display()
		);
	}
	
	protected function xhrExecute() {

		// id comes either via URL or as an extra form field

		$id = urldecode($this->request->query->get('id', $this->request->request->get('id')));

		$app = Application::getInstance();
		$admin = $app->getCurrentUser();
		$db = $app->getDb();

		// editing own record is not allowed
		
		if($admin->getUsername() === $id) {
			return new Response('', Response::HTTP_FORBIDDEN);
		}
		
		if($id) {

			$userRows = $db->doPreparedQuery("SELECT * FROM " . $db->quoteIdentifier('admin') . " WHERE username = ?", [$id]);

			if(!count($userRows)) {
				return new Response('', Response::HTTP_FORBIDDEN);
			}
			
			$userRow = $userRows[0];

		}

		$form = HtmlForm::create('admin_edit_user.htm')
			->addElement(FormElementFactory::create('input',	'username',			NULL,	[],	[],	TRUE, ['trim'], 				[new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Ein Benutzername ist ein Pflichtfeld.'))
			->addElement(FormElementFactory::create('input',	'email',			NULL,	[],	[],	TRUE, ['trim', 'lowercase'],	[new Email()], 'Ungültige E-Mail Adresse.'))
			->addElement(FormElementFactory::create('input',	'name',				NULL,	[],	[],	TRUE, ['trim'],					[new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Name ist ein Pflichtfeld.'))
			->addElement(FormElementFactory::create('password',	'new_PWD',			NULL,	[],	[],	FALSE, [],						[new RegularExpression('/^(|[^\s].{4,}[^\s])$/')], 'Das Passwort muss mindestens 4 Zeichen umfassen.'))
			->addElement(FormElementFactory::create('password',	'new_PWD_verify',	NULL))
			->addElement(FormElementFactory::create('select',	'admingroupsid',	NULL,	[],	[],	TRUE, [],						[new RegularExpression(Rex::INT_EXCL_NULL)], 'Eine Benutzergruppe muss zugewiesen werden.'))
		;

		$v = $form
			->disableCsrfToken()
			->bindRequestParameters($this->request->request)
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

		if(!isset($errors['email']) && (!$id || $v['email'] != strtolower($userRow['email'])) && !Util::isAvailableEmail($v['email'])) {
            $form->setError('email', null, 'Email wird bereits verwendet.');
		}
		if(!isset($errors['username']) && (!$id || $v['username'] !== $userRow['username']) && !Util::isAvailableUsername($v['username'])) {
            $form->setError('username', null, 'Username wird bereits verwendet.');
		}
			
		if(!$form->getFormErrors()) {

			try {

				if($id) {
					$db->updateRecord('admin', ['username' => $id], $v->all());
				}

				else {
					$id = $db->insertRecord('admin', $v->all());
				}

				return new JsonResponse([
					'success' => TRUE,
					'id' => $id
				]);
		
			}
			catch (\Exception $e) {
				return new JsonResponse([
					'success' => FALSE,
					'message' => $e->getMessage()
				]);
			}
		}

        $errors	= $form->getFormErrors();

        $response = [];

        foreach($errors as $element => $error) {
            $response[] = ['name' => $element, 'error' => 1, 'errorText' => $error->getErrorMessage()];
        }

		return new JsonResponse(['elements' => $response]);
		
	}

	protected function getUserData()
    {

        $id = $this->request->query->get('id');
        $db = Application::getInstance()->getVxPDO();

        if ($id) {

        } else {
            $formData = null;
        }

        $formData = $db->doPreparedQuery("SELECT * FROM " . $db->quoteIdentifier('admin') . " WHERE username = ?", ['gregor'])->current();

        $adminGroups = $db->doPreparedQuery("SELECT admingroupsid, name FROM admingroups ORDER BY privilege_level");

        return new JsonResponse([
            'formData' => $formData,
            'options' => [
                'admingroups' => (array)$adminGroups
            ]
        ]);

    }

    protected function postUserData()
    {

        $db = Application::getInstance()->getVxPDO();

        $form = HtmlForm::create('admin_edit_user.htm')
            ->addElement(FormElementFactory::create('input',	'username',			null,	[],	[],	true, ['trim'], 				[new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Benutzername ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create('input',	'email',			null,	[],	[],	true, ['trim', 'lowercase'],	[new Email()], 'Ungültige E-Mail Adresse.'))
            ->addElement(FormElementFactory::create('input',	'name',				null,	[],	[],	true, ['trim'],					[new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Name ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create('password',	'new_PWD',			null,	[],	[],	false, [],						[new RegularExpression('/^(|[^\s].{4,}[^\s])$/')], 'Das Passwort muss mindestens 4 Zeichen umfassen.'))
            ->addElement(FormElementFactory::create('password',	'new_PWD_verify',	null))
            ->addElement(FormElementFactory::create('select',	'admingroupsid',	null,	[],	[],	true, [],						[new RegularExpression(Rex::INT_EXCL_NULL)], 'Eine Benutzergruppe muss zugewiesen werden.'))
        ;

        $request = new ParameterBag(json_decode($this->request->getContent(), true));

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

        if(!($errors = $form->getFormErrors())) {

            try {

                $id = $db->insertRecord('admin', $v->all());

                return new JsonResponse([
                    'success' => true,
                    'user_id' => $id
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

        return new JsonResponse(['success' => false, 'errors' => $response]);


    }


}
