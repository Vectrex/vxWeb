<?php
use vxPHP\User\UserAbstract;
use vxPHP\User\Admin;
use vxPHP\User\Notification\Notification;
use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\FormElement\CheckboxElement;
use vxPHP\Util\Rex;

class admin_profile_page extends page {

	private $notificationCheckBoxes = array();

	public function __construct() {
		parent::__construct();

		$admin					= Admin::getInstance();
		$availableNotifications	= Notification::getAvailableNotifications($admin->getAdmingroup());
		$assignedNotifications	= $admin->getNotifications();

		$this->form = new HtmlForm('admin_profile.htm');
		$this->form->setAttribute('class', 'editProfileForm');

		foreach($availableNotifications as $n) {
			if($n->not_displayed == 1) {
				continue;
			}
			$e = new CheckboxElement($n->alias, 1, $admin->getsNotified($n->alias));
			$e->setLabel("&nbsp;{$n->description}");
			$this->notificationCheckBoxes[] = $e;
			$this->form->addElement($e);
		}

		$button = FormElementFactory::create('button', 'submit_profile', '', array('type' => 'submit', 'class' => 'ml'));
		$button->setInnerHTML('<span>Änderungen speichern</span>');

		$this->form->addElement($button);
		$this->form->addElement(FormElementFactory::create('input',		'Email',			$admin->getId(),		array('maxlength' => 128, 'class' => 'xl', 'id' => 'email_input'),	array(),	FALSE, array('trim', 'lowercase'),	array('/'.Rex::EMAIL.'/i')));
		$this->form->addElement(FormElementFactory::create('input',		'Name',				$admin->getName(),		array('maxlength' => 128, 'class' => 'xl', 'id' => 'name_input'),	array(),	FALSE, array('trim'),				array(Rex::NOT_EMPTY_TEXT)));
		$this->form->addElement(FormElementFactory::create('password',	'new_PWD',			'',						array('maxlength' => 128, 'class' => 'xl', 'id' => 'pwd_input'),	array(),	FALSE, array(),						array('/^(|[^\s]{6,})$/')));
		$this->form->addElement(FormElementFactory::create('password',	'new_PWD_verify',	'',						array('maxlength' => 128, 'class' => 'xl', 'id' => 'pwd2_input')));

		if(isset($this->validatedRequests['success'])) {
			$this->form->initVar('success', 1);
		}

		if(!empty($this->notificationCheckBoxes)) {
			$this->form->initVar('has_notifications', 1);
		}

		if($this->form->wasSubmittedByName('submit_profile')) {
			$this->form->validate();

			if(!$this->form->getFormErrors()) {
				$v = $this->form->getValidFormValues();

				if(!empty($v['new_PWD']) && $v['new_PWD'] != $v['new_PWD_verify']) {
					$this->form->setError('PWD_mismatch');
				}

				if($v['Email'] != $admin->getId() && !UserAbstract::isAvailableId($v['Email'])) {
					$this->form->setError('duplicate_Email');
				}

				if(!$this->form->getFormErrors()) {
					if(!empty($v['new_PWD'])) {
						$v['PWD'] = sha1($v['new_PWD']);
					}

					if($admin->restrictedUpdate($v)) {
						$add = array();
						foreach($availableNotifications as $n) {
							if(!empty($v[$n->alias])) {
								$add[] = $n->alias;
							}
						}
						$admin->setNotifications($add);
						$this->redirect('admin.php?page=profile&success');
					}
					else {
						$this->form->setError('system');
					}
				}
			}
			$this->form->initVar('success', 0);
		}
	}

	public function content() {

		if(!empty($this->notificationCheckBoxes)) {
			$checkBoxHtml = '';
			foreach($this->notificationCheckBoxes as $checkBox) {
				$checkBoxHtml .= "<div class='formItem'>{$checkBox->render()}</div>";
			}
			$this->form->addMiscHtml('notifications', $checkBoxHtml);
		}

		$html = $this->form->render();
		$this->html .= $html;
		return $html;
	}

	protected function handleHttpRequest() {
		$_POST = $this->validatedRequests['elements'];

		$admin					= Admin::getInstance();
		$availableNotifications	= Notification::getAvailableNotifications($admin->getAdmingroup());
		$assignedNotifications	= $admin->getNotifications();

		$f = new HtmlForm('admin_profile.htm');

		foreach($availableNotifications as $n) {
			$f->addElement(new CheckboxElement($n->alias, 1, $admin->getsNotified($n->alias)));
		}

		$f->addElement(FormElementFactory::create('input',		'Email',			$admin->getId(),		array(),	array(),	FALSE, array('trim', 'lowercase'),	array('/'.Rex::EMAIL.'/i')));
		$f->addElement(FormElementFactory::create('input',		'Name',				$admin->getName(),		array(),	array(),	FALSE, array('trim'),				array(Rex::NOT_EMPTY_TEXT)));
		$f->addElement(FormElementFactory::create('password',	'new_PWD',			'',						array(),	array(),	FALSE, array(),						array('/^(|[^\s]{6,})$/')));
		$f->addElement(FormElementFactory::create('password',	'new_PWD_verify',	'',						array()));

		$f->validate();
		if(!$f->getFormErrors()) {

			$v = $f->getValidFormValues();

			if(!empty($v['new_PWD']) && $v['new_PWD'] != $v['new_PWD_verify']) {
				$f->setError('PWD_mismatch');
			}
			if($v['Email'] != $admin->getId() && !UserAbstract::isAvailableId($v['Email'])) {
				$f->setError('duplicate_Email');
			}

			if(!$f->getFormErrors()) {

				if(!empty($v['new_PWD'])) {
					$v['PWD'] = sha1($v['new_PWD']);
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
				return array('success' => 1);
			}
		}

		$errors	= $f->getFormErrors();
		$texts	= $f->getErrorTexts();

		$response = array();
		foreach(array_keys($errors) as $err) {
			$response[] = array('name' => $err, 'error' => 1, 'errorText' => isset($texts[$err]) ? $texts[$err] : NULL);
		}
		return array('elements' => $response);
	}
}
?>