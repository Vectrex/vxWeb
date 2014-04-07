<?php
use vxPHP\User\UserAbstract;
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

class ProfileController extends Controller {

	public function execute() {
		
		$admin					= User::getSessionUser();
		$availableNotifications	= Notification::getAvailableNotifications($admin->getAdmingroup());
		$assignedNotifications	= $admin->getNotifications();

		$checkBoxHtml = '';

		foreach($availableNotifications as $n) {
			if($n->not_displayed == 1) {
				continue;
			}
			$e = new CheckboxElement($n->alias, 1, $admin->getsNotified($n->alias));
			$e->setLabel('&nbsp;' . $n->description);
			$form->addElement($e);

			$checkBoxHtml .= '<div class="formItem">' . $e->render() . '</div>';
		}

		$button = FormElementFactory::create('button', 'submit_profile', '', array('type' => 'submit', 'class' => 'ml'));
		$button->setInnerHTML('<span>Änderungen speichern</span>');

		$form =
			HtmlForm::create('admin_profile.htm')
				->setAttribute('class', 'editProfileForm')
				->addElement($button)
				->addElement(FormElementFactory::create('input',		'Email',		$admin->getId(),		array('maxlength' => 128, 'class' => 'xl', 'id' => 'email_input'),	array(),	FALSE, array('trim', 'lowercase'),	array('/'.Rex::EMAIL.'/i')))
				->addElement(FormElementFactory::create('input',		'Name',			$admin->getName(),		array('maxlength' => 128, 'class' => 'xl', 'id' => 'name_input'),	array(),	FALSE, array('trim'),				array(Rex::NOT_EMPTY_TEXT)))
				->addElement(FormElementFactory::create('password',	'new_PWD',			'',						array('maxlength' => 128, 'class' => 'xl', 'id' => 'pwd_input'),	array(),	FALSE, array(),						array('/^(|[^\s].{4,}[^\s])$/')))
				->addElement(FormElementFactory::create('password',	'new_PWD_verify',	'',						array('maxlength' => 128, 'class' => 'xl', 'id' => 'pwd2_input')))
				->addMiscHtml('notifications', $checkBoxHtml)
				->initVar('success', (int) end($this->pathSegments) === 'success')
				->initVar('has_notifications', (int) !empty($checkBoxHtml));

		if(!$this->isXhr) {

			$form->bindRequestParameters();

			if($form->wasSubmittedByName('submit_profile')) {

				if(!$form->validate()->getFormErrors()) {

					$v = $form->getValidFormValues();

					if(!empty($v['new_PWD']) && $v['new_PWD'] != $v['new_PWD_verify']) {
						$form->setError('PWD_mismatch');
					}

					if($v['Email'] != $admin->getId() && !UserAbstract::isAvailableId($v['Email'])) {
						$form->setError('duplicate_Email');
					}

					if(!$form->getFormErrors()) {
						if(!empty($v['new_PWD'])) {
							$v['PWD'] = Util::hashPassword($v['new_PWD']);
						}

						if($admin->restrictedUpdate($v)) {
							$add = array();
							foreach($availableNotifications as $n) {
								if(!empty($v[$n->alias])) {
									$add[] = $n->alias;
								}
							}
							$admin->setNotifications($add);
							$this->redirect('profile/success');
						}
						else {
							$form->setError('system');
						}
					}
				}
				$form->initVar('success', 0);
			}

			return new Response(
				SimpleTemplate::create('admin/profile.php')
					->assign('form', $form->render())
					->display()
			);
		}

		else {
			$this->request->request->add($this->request->request->get('elements'));
			$form->bindRequestParameters($this->request->request);

			if(!$form->validate()->getFormErrors()) {

				$v = $form->getValidFormValues();

				if(!empty($v['new_PWD']) && $v['new_PWD'] != $v['new_PWD_verify']) {
					$form->setError('PWD_mismatch');
				}
				if($v['Email'] != $admin->getId() && !Util::isAvailableId($v['Email'])) {
					$form->setError('duplicate_Email');
				}

				if(!$form->getFormErrors()) {

					if(!empty($v['new_PWD'])) {
						$v['PWD'] = Util::hashPassword($v['new_PWD']);
					}
					if($admin->restrictedUpdate($v)) {
						$add = array();
						foreach($availableNotifications as $n) {
							if(!empty($v[$n->alias])) {
								$add[] = $n->alias;
							}
						}
						$admin->setNotifications($add);
					}
					return new JsonResponse(array('success' => 1));
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
}
