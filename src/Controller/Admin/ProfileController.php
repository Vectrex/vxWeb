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
                'misc' => $admin->getAttribute('misc') ? json_decode($admin->getAttribute('misc'), true, 512, JSON_THROW_ON_ERROR) : null,
                'notifications' => $notified,
            ],
            'notifications' => array_values(
                array_map(static fn ($notfication) => [

                    /* @var Notification $notification */

                        'alias' => $notfication->alias,
                        'label' => $notfication->description,
                        'id' => $notfication->id
                    ],
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
    protected function update(): JsonResponse
    {
        $request = new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        $admin = Application::getInstance()->getCurrentUser();
        $availableNotifications = Notification::getAvailableNotifications($admin->getRoles()[0]->getRoleName());

        $form = HtmlForm::create()
            ->addElement(FormElementFactory::create(type: 'input', name: 'username', value: $admin->getUsername(), required: true, modifiers: ['trim', 'lowercase'], validators: [new RegularExpression(Rex::NOT_EMPTY_TEXT)], validationErrorMessage: 'Ein Benutzername ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create(type: 'input', name: 'email', value: $admin->getAttribute('email'), required: true, modifiers: ['trim', 'lowercase'], validators: [new Email()], validationErrorMessage: 'Ungültige E-Mail Adresse.'))
            ->addElement(FormElementFactory::create(type: 'input', name: 'name', value: $admin->getAttribute('name'), required: true, modifiers: ['trim'], validators: [new RegularExpression(Rex::NOT_EMPTY_TEXT)], validationErrorMessage: 'Der Name ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create(type: 'password', name: 'new_PWD', value: '', validators: [new RegularExpression('/^(|\S.{4,}\S)$/')], validationErrorMessage: 'Das Passwort muss mindestens 6 Zeichen umfassen.'))
            ->addElement(FormElementFactory::create(type: 'password', name: 'new_PWD_verify', value: ''))
        ;
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
            ->getValidFormValues()
        ;
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

        if (!$form->getFormErrors()) {
            $miscData = $request->get('misc');
            try {
                Application::getInstance()->getVxPDO()->updateRecord('admin', ['username' => $admin->getUsername()], [...$v->all(), 'misc' => $miscData ? json_encode($miscData, JSON_THROW_ON_ERROR) : null]);

                $notifications = $v->get('notifications', []);

                foreach ($availableNotifications as $n) {
                    if (in_array($n->alias, $notifications, true)) {
                        $n->subscribe($admin);
                    } else {
                        $n->unsubscribe($admin);
                    }
                }

                $userProvider = new SessionUserProvider();

                // refresh user data if the username hasn't changed

                if ($v['username'] === $admin->getUsername()) {

                    // changed password sets User::authenticated to false

                    $userProvider->refreshUser($admin);
                } else {
                    $userProvider->unsetSessionUser();
                    $admin = $userProvider->instanceUserByUsername($v['username']);
                }
                $admin->setAuthenticated(true);

                return new JsonResponse(['success' => true, 'message' => 'Daten erfolgreich übernommen.', 'payload' => ['username' => $admin->getUsername(), 'email' => $admin->getAttribute('email')]]);

            } catch (\Exception $e) {
                return new JsonResponse(['success' => false, 'message' => $e->getMessage()]);
            }
        }

        return new JsonResponse([
            'success' => false,
            'errors' => array_map(static fn($error) => $error->getErrorMessage(), $form->getFormErrors()),
            'message' => 'Formulardaten unvollständig oder fehlerhaft.'
        ]);
    }
}
