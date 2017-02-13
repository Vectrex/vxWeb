<?php

namespace App\Controller\Admin;

use vxPHP\Template\SimpleTemplate;
use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Application\Application;
use vxPHP\User\User;
use vxPHP\User\Exception\UserException;
use vxPHP\Util\Rex;
use vxPHP\Http\JsonResponse;
use vxPHP\User\Util;
use vxPHP\Routing\Router;
use vxPHP\Webpage\MenuGenerator;
use vxPHP\Constraint\Validator\RegularExpression;
use vxPHP\Constraint\Validator\Email;

class UsersController extends Controller {

	protected function execute() {

		$app = Application::getInstance();
		$admin = $app->getCurrentUser();
		$db = $app->getDb();
		
		$connection		= $app->getDb()->getConnection();
		$redirectUrl	= Router::getRoute('users', 'admin.php')->getUrl();

		// editing or deleting something? Ensure user exists

		if(($id = $this->request->query->getInt('id'))) {

			$userRows = $db->doPreparedQuery("SELECT a.* FROM admin a WHERE a.adminID = ?", [$id]);
				
			if(!count($userRows)) {
				return $this->redirect($redirectUrl);
			}

			$userRow = $userRows[0];

			// editing own record is not allowed

			if($admin->getUsername() == $userRow['username']) {
				return $this->redirect($redirectUrl);
			}

		}
		
		// delete user
		
		if($id && count($this->pathSegments) > 1 && end($this->pathSegments) == 'del') {
		
			$db->deleteRecord('admin', $id);

			return $this->redirect($redirectUrl);
			
		}

		// edit or add user
		
		if($id || count($this->pathSegments) > 1 && end($this->pathSegments) == 'new') {

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
				->addElement(FormElementFactory::create('select',	'admingroupsID',		NULL, [], $admingroups));
			
			$form->bindRequestParameters();
			
			return new Response(
				SimpleTemplate::create('admin/users_edit.php')
					->assign('user', $userRow)
					->assign('form', $form->render())
					->display()
			);
		}
		
		$users	= $db->doPreparedQuery("SELECT a.*, ag.alias FROM admin a LEFT JOIN admingroups ag ON ag.admingroupsID = a.admingroupsID", []);

		return new Response(
			SimpleTemplate::create('admin/users_list.php')
				->assign('users', $users)
				->display()
		);
	}
	
	protected function xhrExecute() {

		// id comes either via URL or as an extra form field

		$id = $this->request->query->getInt('id', $this->request->request->getInt('id'));

		// editing own record is not allowed
		
		if(User::getSessionUser()->getAdminId() == $id) {
			return new Response('', Response::HTTP_FORBIDDEN);
		}
		
		if($id) {

			try {
				$user = User::getInstance($id);
			}
			catch(UserException $e) {
				return new JsonResponse([
					'success' => FALSE,
					'message' => $e->getMessage()
				]);
			}
		
		}

		else {
			$user = new User();
		}

		$admingroups = Application::getInstance()
			->getDb()
			->getConnection()
			->query('SELECT alias, name FROM admingroups ORDER BY privilege_level')->fetchAll(\PDO::FETCH_KEY_PAIR);

		$form = HtmlForm::create('admin_edit_user.htm')
			->addElement(FormElementFactory::create('input',	'username',			NULL,	[],	[],	TRUE, ['trim'], 				[new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
			->addElement(FormElementFactory::create('input',	'email',			NULL,	[],	[],	TRUE, ['trim', 'lowercase'],	[new Email()]))
			->addElement(FormElementFactory::create('input',	'name',				NULL,	[],	[],	TRUE, ['trim'],					[new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
			->addElement(FormElementFactory::create('password',	'new_PWD',			NULL,	[],	[],	FALSE, [],						[new RegularExpression('/^(|[^\s].{4,}[^\s])$/')]))
			->addElement(FormElementFactory::create('password',	'new_PWD_verify',	NULL))
			->addElement(FormElementFactory::create('select',	'admingroup',		NULL,	[],	[],	TRUE, [],						[new RegularExpression('/^(' . implode('|', array_keys($admingroups)) . ')$/i')]));
		
		$v = $form
				->disableCsrfToken()
				->bindRequestParameters($this->request->request)
				->validate()
				->getValidFormValues();

		$errors = $form->getFormErrors();

		if(!isset($errors['new_PWD'])) {

			if(!empty($v['new_PWD'])) {
				if($v['new_PWD'] != $v['new_PWD_verify']) {
					$form->setError('PWD_mismatch');
				}
				else {
					$user->setPassword($v['new_PWD']);
				}
			}
		}

		if(!isset($errors['email']) && $v['email'] != strtolower($user->getEmail()) && !Util::isAvailableEmail($v['email'])) {
			$form->setError('duplicate_email');
		}
		if(!isset($errors['username']) && $v['username'] != $user->getUsername() && !Util::isAvailableUsername($v['username'])) {
			$form->setError('duplicate_username');
		}
			
		if(!$form->getFormErrors()) {
	
			try {

				$user
					->setUsername	($v['username'])
					->setName		($v['name'])
					->setEmail		($v['email'])
					->setAdmingroup	($v['admingroup'])
					->save			();

				return new JsonResponse([
					'success' => TRUE,
					'id' => $user->getAdminId()
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
		$texts	= $form->getErrorTexts();

		$response = [];
		
		foreach(array_keys($errors) as $err) {
			$response[] = [
				'name' => $err,
				'error' => 1,
				'errorText' => isset($texts[$err]) ? $texts[$err] : NULL
			];
		}
		
		return new JsonResponse(['elements' => $response]);
		
	}
}
