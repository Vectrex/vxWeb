<?php

namespace App\Controller\Admin;

use vxPHP\Application\Application;
use vxPHP\Application\Exception\ApplicationException;
use vxPHP\Application\Exception\ConfigException;
use vxPHP\Constraint\Validator\Email;
use vxPHP\Constraint\Validator\RegularExpression;
use vxPHP\Controller\Controller;
use vxPHP\Form\Exception\FormElementFactoryException;
use vxPHP\Form\Exception\HtmlFormException;
use vxPHP\Form\FormElement\CheckboxElement;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\HtmlForm;
use vxPHP\Http\JsonResponse;
use vxPHP\Http\ParameterBag;
use vxPHP\Http\Response;
use vxPHP\Security\Csrf\Exception\CsrfTokenException;
use vxPHP\Security\Password\PasswordEncrypter;
use vxPHP\Util\Rex;
use vxWeb\User\Notification\Notification;
use vxWeb\User\SessionUserProvider;
use vxWeb\User\Util;

class ProfileController extends Controller
{
    /**
     * @return JsonResponse
     * @throws \Throwable
     * @throws ApplicationException
     */
    protected function get(): JsonResponse
    {
        $admin = Application::getInstance()->getCurrentUser();
        if (!$admin) {
            return new JsonResponse(['message' => 'Authentication failed.'], Response::HTTP_UNAUTHORIZED);
        }

        $notifications = array_filter(
            Notification::getAvailableNotifications($admin->getRoles()[0]->getRoleName()),
            static function ($notification) {
                return !$notification->not_displayed;
            }
        );

        $notified = [];

        foreach ($notifications as $notification) {
            if ($notification->notifies($admin)) {
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
                array_map(static function ($notfication) {

                    /* @var Notification $notification */

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

    /**
     * @return JsonResponse
     * @throws ApplicationException
     * @throws \JsonException
     * @throws \Throwable
     * @throws ConfigException
     * @throws FormElementFactoryException
     * @throws HtmlFormException
     * @throws CsrfTokenException
     */
    protected function post(): JsonResponse
    {
        $request = new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        $admin = Application::getInstance()->getCurrentUser();
        $availableNotifications = Notification::getAvailableNotifications($admin->getRoles()[0]->getRoleName());

        $form = HtmlForm::create()
            ->addElement(FormElementFactory::create('input', 'username', $admin->getUsername(), [], [], true, ['trim', 'lowercase'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Ein Benutzername ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create('input', 'email', $admin->getAttribute('email'), [], [], true, ['trim', 'lowercase'], [new Email()], 'Ungültige E-Mail Adresse.'))
            ->addElement(FormElementFactory::create('input', 'name', $admin->getAttribute('name'), [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Name ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create('password', 'new_PWD', '', [], [], false, [], [new RegularExpression('/^(|\S.{4,}\S)$/')], 'Das Passwort muss mindestens 6 Zeichen umfassen.'))
            ->addElement(FormElementFactory::create('password', 'new_PWD_verify', ''));

        $checkboxes = [];

        foreach ($availableNotifications as $n) {
            if ($n->not_displayed !== 1) {
                $checkboxes[] = new CheckboxElement('notifications', $n->alias);
            }
        }

        $form->addElementArray($checkboxes);

        $v = $form
            ->disableCsrfToken()
            ->bindRequestParameters($request)
            ->validate()
            ->getValidFormValues();

        if (!isset($errors['new_PWD']) && !empty($v['new_PWD'])) {
            if ($v['new_PWD'] !== $v['new_PWD_verify']) {
                $form->setError('new_PWD_verify', null, 'Passwörter stimmen nicht überein.');
            } else {
                $v['pwd'] = (new PasswordEncrypter())->hashPassword($v['new_PWD']);
            }
        }


        if (!$form->getFormErrors()) {
            if ($v['email'] !== $admin->getAttribute('email') && !Util::isAvailableEmail($v['email'])) {
                $form->setError('email', null, 'Email wird bereits verwendet.');
            }

            if ($v['username'] !== $admin->getUsername() && !Util::isAvailableUsername($v['username'])) {
                $form->setError('username', null, 'Username wird bereits verwendet.');
            }
        }

        if (!($errors = $form->getFormErrors())) {

            try {
                Application::getInstance()->getVxPDO()->updateRecord('admin', ['username' => $admin->getUsername()], $v->all());

                $notifications = $v->get('notifications', []);

                foreach ($availableNotifications as $n) {
                    if (in_array($n->alias, $notifications, true)) {
                        $n->subscribe($admin);
                    } else {
                        $n->unsubscribe($admin);
                    }
                }

                $userProvider = new SessionUserProvider();

                // refresh user data if username hasn't changed

                if ($v['username'] === $admin->getUsername()) {

                    // a changed password sets User::authenticated to false

                    $userProvider->refreshUser($admin);
                } else {
                    $userProvider->unsetSessionUser();
                    $admin = $userProvider->instanceUserByUsername($v['username']);
                }
                $admin->setAuthenticated(true);

                return new JsonResponse(['success' => true, 'message' => 'Daten erfolgreich übernommen.']);

            } catch (\Exception $e) {
                return new JsonResponse(['success' => false, 'message' => $e->getMessage()]);
            }
        }

        return new JsonResponse([
            'success' => false,
            'errors' => array_map(fn($error) => $error->getMessage(), $form->getFormErrors()),
            'message' => 'Formulardaten unvollständig oder fehlerhaft.'
        ]);
    }
}
