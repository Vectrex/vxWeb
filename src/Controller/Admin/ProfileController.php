<?php

namespace App\Controller\Admin;

use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\FormElement\CheckboxElement;
use vxPHP\Form\FormElement\LabelElement;

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
use vxWeb\User\Notification\Notification;

class ProfileController extends Controller {

	public function execute() {

		$admin = Application::getInstance()->getCurrentUser();
		$availableNotifications	= Notification::getAvailableNotifications($admin->getRoles()[0]->getRoleName());

		$form =
			HtmlForm::create('admin_profile.htm')
				->addElement(FormElementFactory::create('input', 'username', $admin->getUsername(),	[],	[], true, ['trim', 'lowercase'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Ein Benutzername ist ein Pflichtfeld.'))
				->addElement(FormElementFactory::create('input', 'email', $admin->getAttribute('email'), [], [], true, ['trim', 'lowercase'], [new Email()], 'Ungültige E-Mail Adresse.'))
				->addElement(FormElementFactory::create('input', 'name', $admin->getAttribute('name'), [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Name ist ein Pflichtfeld.'))
				->addElement(FormElementFactory::create('password',	'new_PWD', '', [], [],	false, [], [new RegularExpression('/^(|[^\s].{4,}[^\s])$/')], 'Das Passwort muss mindestens 4 Zeichen umfassen.'))
				->addElement(FormElementFactory::create('password',	'new_PWD_verify', ''))
				->addElement(FormElementFactory::create('button', 'submit_profile', '')->setInnerHTML('Änderungen speichern'))
				->initVar('has_notifications', 0);

        $checkbox = [];

		foreach($availableNotifications as $n) {
            if ($n->not_displayed !== 1) {
                $checkbox[] = (new CheckboxElement('notification', $n->alias, $n->notifies($admin)))->setLabel(new LabelElement($n->description));
            }
        }

        if(count($checkbox)) {

            $checkBoxHtml = '';
            $form->initVar('has_notifications', 1);
            $form->addElementArray($checkbox);

            foreach($checkbox as $ndx => $e) {
                $checkBoxHtml .= '<div class="form-group"><label class="form-switch">' . $e->render() . '<i class="form-icon"></i>' . $e->getLabel()->getLabelText() . '</label></div>';
            }

            $form->addMiscHtml('notifications', $checkBoxHtml);

        }

		if($this->request->getMethod() === 'POST') {

			$form->bindRequestParameters($this->request->request);

			if(!$form->validate()->getFormErrors()) {

				$v = $form->getValidFormValues();

				if(!empty($v['new_PWD'])) {
					if($v['new_PWD'] != $v['new_PWD_verify']) {
						$form->setError('new_PWD_verify', null, 'Passwörter stimmen nicht überein.');
					}
					else {
						$v['pwd'] = (new PasswordEncrypter())->hashPassword($v['new_PWD']);
					}
				}

				if($v['email'] !== $admin->getAttribute('email') && !Util::isAvailableEmail($v['email'])) {
					$form->setError('email', null, 'Email wird bereits verwendet.');
				}

				if($v['username'] !== $admin->getUsername() && !Util::isAvailableUsername($v['username'])) {
					$form->setError('username', null, 'Username wird bereits verwendet.');
				}

				if(!$form->getFormErrors()) {

					try {
						Application::getInstance()->getDb()->updateRecord('admin', ['username' => $admin->getUsername()], $v->all());

						$notifications = $v->get('notification', '');

						foreach($availableNotifications as $n) {
							if(in_array($n->alias, $notifications)) {
								$n->subscribe($admin);
							}
							else {
								$n->unsubscribe($admin);
							}
						}

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

						return new JsonResponse(['success' => true]);

					}
					catch (\Exception $e) {
						return new JsonResponse(['success' => false, 'message' => $e->getMessage()]);
					}
				}
			}

			$errors	= $form->getFormErrors();

			$response = [];

			foreach($errors as $element => $error) {
				$response[] = ['name' => $element, 'error' => 1, 'errorText' => $error->getErrorMessage()];
			}

			return new JsonResponse(['success' => false, 'elements' => $response]);

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
