<?php

namespace App\Controller\Admin;

use vxPHP\User\Notification\Notification;

use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\FormElement\CheckboxElement;

use vxPHP\Util\Rex;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Http\JsonResponse;
use vxPHP\Constraint\Validator\RegularExpression;
use vxPHP\Constraint\Validator\Email;
use vxPHP\Application\Application;
use vxPHP\Security\Password\PasswordEncrypter;

use vxWeb\User\SessionUserProvider;
use vxWeb\User\Util;

class ProfileController extends Controller {

	public function execute() {
		
		$admin = Application::getInstance()->getCurrentUser();
		$availableNotifications	= [];//Notification::getAvailableNotifications($admin->getAdmingroup());
		
		$checkBoxHtml = '';

		$form =
			HtmlForm::create('admin_profile.htm')
				->addElement(FormElementFactory::create('input',	'username',			$admin->getUsername(),	[],	[], TRUE, ['trim', 'lowercase'],	[new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
				->addElement(FormElementFactory::create('input',	'email',			$admin->getAttribute('email'),		[], [], TRUE, ['trim', 'lowercase'],	[new Email()]))
				->addElement(FormElementFactory::create('input',	'name',				$admin->getAttribute('name'),		[], [], TRUE, ['trim'],					[new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
				->addElement(FormElementFactory::create('password',	'new_PWD',			'',						[], [],	FALSE, [],						[new RegularExpression('/^(|[^\s].{4,}[^\s])$/')]))
				->addElement(FormElementFactory::create('password',	'new_PWD_verify',	''))
				->addElement(FormElementFactory::create('button', 'submit_profile', '')->setInnerHTML('Änderungen speichern'))
				->initVar('success', (int) end($this->pathSegments) === 'success')
				->initVar('has_notifications', (int) !empty($checkBoxHtml));

		foreach($availableNotifications as $n) {
			if($n->not_displayed == 1) {
				continue;
			}

			$form->initVar('has_notifications', 1);

			$e = new CheckboxElement($n->alias, 1, $admin->getsNotified($n->alias));
			$e->setLabel('&nbsp;' . $n->description);
			$form->addElement($e);

			$checkBoxHtml .= '<div class="formItem">' . $e->render() . '</div>';
		}
		
		$form->addMiscHtml('notifications', $checkBoxHtml);

		if($this->request->getMethod() === 'POST') {

			$form->bindRequestParameters($this->request->request);

			if(!$form->validate()->getFormErrors()) {

				$v = $form->getValidFormValues();

				if(!empty($v['new_PWD'])) {
					if($v['new_PWD'] != $v['new_PWD_verify']) {
						$form->setError('PWD_mismatch');
					}
					else {
						$v['pwd'] = (new PasswordEncrypter())->hashPassword($v['new_PWD']);
					}
				}

				if($v['email'] != $admin->getAttribute('email') && !Util::isAvailableEmail($v['email'])) {
					$form->setError('duplicate_email');
				}

				if($v['username'] != $admin->getUsername() && !Util::isAvailableUsername($v['username'])) {
					$form->setError('duplicate_username');
				}

				if(!$form->getFormErrors()) {

					try {
						Application::getInstance()->getDb()->updateRecord('admin', ['username' => $admin->getUsername()], $v->all());

						/*
						$add = [];

						foreach($availableNotifications as $n) {
							if(!empty($v[$n->alias])) {
								$add[] = $n->alias;
							}
						}

						$admin->setNotifications($add);
						*/
						$userProvider = new SessionUserProvider();

						// refresh user data if username hasn't changed

						if($v['username'] === $admin->getUsername()) {
							$userProvider->refreshUser($admin);
						}
						else {
							$previousUser = $userProvider->unsetSessionUser();
							$admin = $userProvider->instanceUserByUsername($v['username']);
							$admin->setAuthenticated($previousUser->isAuthenticated());
						}

						return new JsonResponse(['success' => TRUE]);

					}
					catch (\Exception $e) {
						return new JsonResponse(['success' => FALSE, 'message' => $e->getMessage()]);
					}
				}
			}

			$errors	= $form->getFormErrors();
			$texts	= $form->getErrorTexts();

			$response = [];

			foreach(array_keys($errors) as $err) {
				$response[] = ['name' => $err, 'error' => 1, 'errorText' => isset($texts[$err]) ? $texts[$err] : NULL];
			}

			return new JsonResponse(['success' => FALSE, 'elements' => $response]);

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
