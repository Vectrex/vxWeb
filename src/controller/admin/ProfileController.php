<?php
use vxPHP\User\Util;
use vxPHP\User\Notification\Notification;

use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\FormElement\CheckboxElement;

use vxPHP\Util\Rex;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Http\JsonResponse;
use vxPHP\User\User;
use vxPHP\User\Exception\UserException;

class ProfileController extends Controller {

	public function execute() {
		
		$admin					= User::getSessionUser();
		$availableNotifications	= Notification::getAvailableNotifications($admin->getAdmingroup());
		$assignedNotifications	= $admin->getNotifications();

		$checkBoxHtml = '';

		$form =
			HtmlForm::create('admin_profile.htm')
				->setAttribute('class', 'editProfileForm')
				->addElement(FormElementFactory::create('input',	'username',			$admin->getUsername(),	array('autocomplete' => 'off', 	'maxlength' => 128, 'class' => 'xl', 'id' => 'username_input'),	array(),	FALSE, array('trim', 'lowercase'),	array(Rex::NOT_EMPTY_TEXT)))
				->addElement(FormElementFactory::create('input',	'email',			$admin->getEmail(),		array('autocomplete' => 'off', 	'maxlength' => 128, 'class' => 'xl', 'id' => 'email_input'),		array(),	FALSE, array('trim', 'lowercase'),	array('/'.Rex::EMAIL.'/i')))
				->addElement(FormElementFactory::create('input',	'name',				$admin->getName(),		array('autocomplete' => 'off', 	'maxlength' => 128, 'class' => 'xl', 'id' => 'name_input'),		array(),	FALSE, array('trim'),				array(Rex::NOT_EMPTY_TEXT)))
				->addElement(FormElementFactory::create('password',	'new_PWD',			'',						array('autocomplete' => 'off', 	'maxlength' => 128, 'class' => 'xl', 'id' => 'pwd_input'),		array(),	FALSE, array(),						array('/^(|[^\s].{4,}[^\s])$/')))
				->addElement(FormElementFactory::create('password',	'new_PWD_verify',	'',						array('autocomplete' => 'off', 	'maxlength' => 128, 'class' => 'xl', 'id' => 'pwd2_input')))
				->addElement(FormElementFactory::create('button', 'submit_profile', '', array('type' => 'submit'))->setInnerHTML('Änderungen speichern'))
				->addMiscHtml('notifications', $checkBoxHtml)
				->initVar('success', (int) end($this->pathSegments) === 'success')
				->initVar('has_notifications', (int) !empty($checkBoxHtml));

		foreach($availableNotifications as $n) {
			if($n->not_displayed == 1) {
				continue;
			}

			$e = new CheckboxElement($n->alias, 1, $admin->getsNotified($n->alias));
			$e->setLabel('&nbsp;' . $n->description);
			$form->addElement($e);

			$checkBoxHtml .= '<div class="formItem">' . $e->render() . '</div>';
		}

		if($this->request->getMethod() === 'POST') {

			$form->bindRequestParameters($this->request->request);

			if(!$form->validate()->getFormErrors()) {

				$v = $form->getValidFormValues();

				if(!empty($v['new_PWD'])) {
					if($v['new_PWD'] != $v['new_PWD_verify']) {
						$form->setError('PWD_mismatch');
					}
					else {
						$admin->setPassword($v['new_PWD']);
					}
				}

				if($v['email'] != $admin->getEmail() && !Util::isAvailableEmail($v['email'])) {
					$form->setError('duplicate_email');
				}

				if($v['username'] != $admin->getUsername() && !Util::isAvailableUsername($v['username'])) {
					$form->setError('duplicate_username');
				}

				if(!$form->getFormErrors()) {

					try {
						$admin
							->setUsername	($v['username'])
							->setName		($v['name'])
							->setEmail		($v['email'])
							->save			();
	
						$add = array();

						foreach($availableNotifications as $n) {
							if(!empty($v[$n->alias])) {
								$add[] = $n->alias;
							}
						}
						$admin->setNotifications($add);
						
						return new JsonResponse(array('success' => TRUE));

					}
					catch (\Exception $e) {
						return new JsonResponse(array('success' => FALSE, 'message' => $e->getMessage()));
					}
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
		
		else {
			
			return new Response(
				SimpleTemplate::create('admin/profile.php')
					->assign('form', $form->render())
					->display()
			);
			
		}
	}
}
