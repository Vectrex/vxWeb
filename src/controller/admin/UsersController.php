<?php
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

class UsersController extends Controller {

	protected function execute() {

		$admin			= User::getSessionUser();
		$db				= Application::getInstance()->getDb();
		$redirectUrl	= Router::getRoute('users', 'admin.php')->getUrl();

		// editing or deleting something?

		if(($id = $this->request->query->getInt('id'))) {

			// editing own record is not allowed

			if($admin->getAdminId() == $id) {
				return $this->redirect($redirectUrl);
			}

			try {
				$user = User::getInstance($id);
			}
			catch(UserException $e) {
				return $this->redirect($redirectUrl);
			}

		}
		
		// delete user
		
		if(isset($user) && count($this->pathSegments) > 1 && end($this->pathSegments) == 'del') {
		
			try {
				$user->delete();
			}
			catch(ArticleException $e) { }
		
			return $this->redirect($redirectUrl);
		}

		// edit or add article
		
		if(isset($user) || count($this->pathSegments) > 1 && end($this->pathSegments) == 'new') {

			MenuGenerator::setForceActiveMenu(TRUE);

			$form = HtmlForm::create('admin_edit_user.htm')->setAttribute('class', 'editUserForm');
			
			if(isset($user)) {

				$form->setInitFormValues(array(
					'username'		=> $user->getUsername(),
					'email'			=> $user->getEmail(),
					'name'			=> $user->getName(),
					'admingroup'	=> $user->getAdmingroup()
				));

				$submitLabel = 'Änderungen übernehmen';
			
			}
			
			else {
			
				$user = new User();
			
				$submitLabel = 'User anlegen';
				$form->initVar('is_add', 1);
			}

			$admingroups = $db->query('SELECT LOWER(alias) AS alias, name FROM admingroups ORDER BY privilege_level')->fetchAll(PDO::FETCH_KEY_PAIR);

			$form
				->addElement(FormElementFactory::create('button', 'submit_user', '', array('type' => 'submit'))->setInnerHTML($submitLabel))
				->addElement(FormElementFactory::create('input',	'username',			NULL,	array('autocomplete' => 'off', 	'maxlength' => 128, 'class' => 'xl', 'id' => 'username_input')))
				->addElement(FormElementFactory::create('input',	'email',			NULL,	array('autocomplete' => 'off', 	'maxlength' => 128, 'class' => 'xl', 'id' => 'email_input')))
				->addElement(FormElementFactory::create('input',	'name',				NULL,	array('autocomplete' => 'off', 	'maxlength' => 128, 'class' => 'xl', 'id' => 'name_input')))
				->addElement(FormElementFactory::create('password',	'new_PWD',			'',		array('autocomplete' => 'off', 	'maxlength' => 128, 'class' => 'xl', 'id' => 'pwd_input')))
				->addElement(FormElementFactory::create('password',	'new_PWD_verify',	'',		array('autocomplete' => 'off', 	'maxlength' => 128, 'class' => 'xl', 'id' => 'pwd2_input')))
				->addElement(FormElementFactory::create('select',	'admingroup',		NULL,	array('size' => 1, 'class' => 'xl', 'id' => 'admingroup_select'), $admingroups));
			
			$form->bindRequestParameters();
			
			return new Response(
				SimpleTemplate::create('admin/users_edit.php')
					->assign('user', $user)
					->assign('form', $form->render())
					->display()
			);
		}
		
		$stmt	= $db->query('SELECT adminID FROM admin');
		$users	= array();

		foreach($stmt->fetchAll(\PDO::FETCH_COLUMN, 0) as $id) {
			$users[] = User::getInstance((int) $id);
		}

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
				return new JsonResponse(array('success' => FALSE, 'message' => $e->getMessage()));
			}
		
		}

		else {
			$user = new User();
		}

		$admingroups = Application::getInstance()->getDb()->query('SELECT alias, name FROM admingroups ORDER BY privilege_level')->fetchAll(PDO::FETCH_KEY_PAIR);

		$form = HtmlForm::create('admin_edit_user.htm')
			->addElement(FormElementFactory::create('input',	'username',			NULL,	array(),	array(),	FALSE, array('trim'),	array(Rex::NOT_EMPTY_TEXT)))
			->addElement(FormElementFactory::create('input',	'email',			NULL,	array(),	array(),	FALSE, array('trim', 'lowercase'),	array(Rex::EMAIL)))
			->addElement(FormElementFactory::create('input',	'name',				NULL,	array(),	array(),	FALSE, array('trim'),				array(Rex::NOT_EMPTY_TEXT)))
			->addElement(FormElementFactory::create('password',	'new_PWD',			NULL,	array(),	array(),	FALSE, array(),						array('/^(|[^\s].{4,}[^\s])$/')))
			->addElement(FormElementFactory::create('password',	'new_PWD_verify',	NULL	))
			->addElement(FormElementFactory::create('select',	'admingroup',		NULL,	array(),	array(),	FALSE, array(),						array('/^(' . implode('|', array_keys($admingroups)) . ')$/i')));
		
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

				return new JsonResponse(array('success' => TRUE, 'id' => $user->getAdminId()));
		
			}
			catch (\Exception $e) {
				return new JsonResponse(array('success' => FALSE, 'message' => $e->getMessage()));
			}
		}
		
		$errors	= $form->getFormErrors();
		$texts	= $form->getErrorTexts();

		$response = array();
		
		foreach(array_keys($errors) as $err) {
			$response[] = array('name' => $err, 'error' => 1, 'errorText' => isset($texts[$err]) ? $texts[$err] : NULL);
		}
		
		return new JsonResponse(array('elements' => $response));
		
	}
}
