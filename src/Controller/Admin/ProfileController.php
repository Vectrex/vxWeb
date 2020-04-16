<?php

namespace App\Controller\Admin;

use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\FormElement\CheckboxElement;
use vxPHP\Form\FormElement\LabelElement;

use vxPHP\Http\ParameterBag;
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

	public function execute(): Response
    {
        return new Response(
            SimpleTemplate::create('admin/profile.php')
                ->display()
        );
	}

    protected function get(): JsonResponse
    {
        $admin = Application::getInstance()->getCurrentUser();
        $notifications = array_filter(
            Notification::getAvailableNotifications($admin->getRoles()[0]->getRoleName()),
            function($notification) {
                return !$notification->not_displayed;
            }
        );

        $notified = [];

        foreach($notifications as $notification) {
            if($notification->notifies($admin)) {
                $notified[] = $notification->alias;
            }
        }

        return new JsonResponse([
            'formData' => [
                'username' => $admin->getUsername(),
                'email' => $admin->getAttribute('email'),
                'name' => $admin->getAttribute('name'),
                'notifications' => $notified
            ],
            'notifications' => array_values(
                array_map(function($notfication) {

                        /* @var \vxWeb\User\Notification\Notification $notification */

                        return [
                            'alias' => $notfication->alias,
                            'label' => $notfication->description,
                            'id' => $notfication->id
                        ];

                    },
                    $notifications
                )
            )
        ]);
    }

    protected function post(): JsonResponse
    {
        $request = new ParameterBag(json_decode($this->request->getContent(), true));
        $admin = Application::getInstance()->getCurrentUser();
        $availableNotifications = Notification::getAvailableNotifications($admin->getRoles()[0]->getRoleName());

        $form = HtmlForm::create()
            ->addElement(FormElementFactory::create('input', 'username', $admin->getUsername(), [], [], true, ['trim', 'lowercase'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Ein Benutzername ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create('input', 'email', $admin->getAttribute('email'), [], [], true, ['trim', 'lowercase'], [new Email()], 'Ungültige E-Mail Adresse.'))
            ->addElement(FormElementFactory::create('input', 'name', $admin->getAttribute('name'), [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Name ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create('password', 'new_PWD', '', [], [], false, [], [new RegularExpression('/^(|[^\s].{4,}[^\s])$/')], 'Das Passwort muss mindestens 6 Zeichen umfassen.'))
            ->addElement(FormElementFactory::create('password', 'new_PWD_verify', ''))
        ;

        $checkboxes = [];

        foreach($availableNotifications as $n) {
            if ($n->not_displayed !== 1) {
                $checkboxes[] = new CheckboxElement('notifications', $n->alias);
            }
        }

        $form->addElementArray($checkboxes);

        $v = $form
            ->disableCsrfToken()
            ->bindRequestParameters($request)
            ->validate()
            ->getValidFormValues()
        ;

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

        if($v['email'] !== $admin->getAttribute('email') && !Util::isAvailableEmail($v['email'])) {
            $form->setError('email', null, 'Email wird bereits verwendet.');
        }

        if($v['username'] !== $admin->getUsername() && !Util::isAvailableUsername($v['username'])) {
            $form->setError('username', null, 'Username wird bereits verwendet.');
        }

        if(!($errors = $form->getFormErrors())) {

            try {
                Application::getInstance()->getDb()->updateRecord('admin', ['username' => $admin->getUsername()], $v->all());

                $notifications = $v->get('notifications', []);

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

                return new JsonResponse(['success' => true, 'message' => 'Daten erfolgreich übernommen.']);

            }
            catch (\Exception $e) {
                return new JsonResponse(['success' => false, 'message' => $e->getMessage()]);
            }
        }

        $response = [];

        foreach($errors as $element => $error) {
            $response[$element] = $error->getErrorMessage();
        }

        return new JsonResponse(['success' => false, 'errors' => $response, 'message' => 'Formulardaten unvollständig oder fehlerhaft.']);
    }
}
