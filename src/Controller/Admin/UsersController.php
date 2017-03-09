<?php

namespace App\Controller\Admin;

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
		
		$connection		= $app->getDb()->getConnection();
		$redirectUrl	= Router::getRoute('users', 'admin.php')->getUrl();

		// editing or deleting something? Ensure user exists

		if(($id = $this->request->query->get('id'))) {

			// editing own record is not allowed
			
			if($admin->getUsername() == $id) {
				return $this->redirect($redirectUrl);
			}
				
			$userRows = $db->doPreparedQuery("SELECT a.* FROM admin a WHERE a.username = ?", [$id]);
				
			if(!count($userRows)) {
				return $this->redirect($redirectUrl);
			}

			$userRow = $userRows[0];

		}
		
		// delete user
		
		if($id && count($this->pathSegments) > 1 && end($this->pathSegments) == 'del') {
		
			$db->deleteRecord('admin', ['username' => $id]);

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
				->addElement(FormElementFactory::create('select',	'admingroupsid', NULL, [], $admingroups));
			
			$form->bindRequestParameters();
			
			return new Response(
				SimpleTemplate::create('admin/users_edit.php')
					->assign('user', isset($userRow) ? $userRow : NULL)
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

		$id = $this->request->query->get('id', $this->request->request->get('id'));

		$app = Application::getInstance();
		$admin = $app->getCurrentUser();
		$db = $app->getDb();

		// editing own record is not allowed
		
		if($admin->getUsername() === $id) {
			return new Response('', Response::HTTP_FORBIDDEN);
		}
		
		if($id) {

			$userRows = $db->doPreparedQuery("SELECT a.* FROM admin a WHERE a.username = ?", [$id]);
			
			if(!count($userRows)) {
				return new Response('', Response::HTTP_FORBIDDEN);
			}
			
			$userRow = $userRows[0];

		}

		$form = HtmlForm::create('admin_edit_user.htm')
			->addElement(FormElementFactory::create('input',	'username',			NULL,	[],	[],	TRUE, ['trim'], 				[new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
			->addElement(FormElementFactory::create('input',	'email',			NULL,	[],	[],	TRUE, ['trim', 'lowercase'],	[new Email()]))
			->addElement(FormElementFactory::create('input',	'name',				NULL,	[],	[],	TRUE, ['trim'],					[new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
			->addElement(FormElementFactory::create('password',	'new_PWD',			NULL,	[],	[],	FALSE, [],						[new RegularExpression('/^(|[^\s].{4,}[^\s])$/')]))
			->addElement(FormElementFactory::create('password',	'new_PWD_verify',	NULL))
			->addElement(FormElementFactory::create('select',	'admingroupsid',	NULL,	[],	[],	TRUE, [],						[new RegularExpression(Rex::INT_EXCL_NULL)]))
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
				if($v['new_PWD'] != $v['new_PWD_verify']) {
					$form->setError('PWD_mismatch');
				}
				else {
					$v['pwd'] = (new PasswordEncrypter())->hashPassword($v['new_PWD']);
				}
			}
		}

		if(!isset($errors['email']) && (!$id || $v['email'] != strtolower($userRow['email'])) && !Util::isAvailableEmail($v['email'])) {
			$form->setError('duplicate_email');
		}
		if(!isset($errors['username']) && (!$id || $v['username'] != $userRow['username']) && !Util::isAvailableUsername($v['username'])) {
			$form->setError('duplicate_username');
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
