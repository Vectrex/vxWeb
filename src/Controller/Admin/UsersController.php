<?php

namespace App\Controller\Admin;

use vxPHP\Http\ParameterBag;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Application\Application;
use vxPHP\Util\Rex;
use vxPHP\Http\JsonResponse;
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
		
        $redirectUrl = $app->getRouter()->getRoute('users')->getUrl();
		$action = $this->route->getPathParameter('action');

		// editing or deleting something? Ensure user exists

		if(($id = urldecode($this->request->query->get('id')))) {

			// editing own record is not allowed
			
			if($admin->getUsername() == $id) {
				return $this->redirect($redirectUrl);
			}

			$userRow = $db->doPreparedQuery(sprintf("SELECT * FROM %s WHERE username = ?", $db->quoteIdentifier('admin')), [$id])->current();
			if(!$userRow) {
				return $this->redirect($redirectUrl);
			}
		}
		else {
            $userRow = [];
        }
		
		// delete user
		
		if($id && $action === 'del') {
		
			$db->deleteRecord('admin', ['username' => $id]);

			return $this->redirect($redirectUrl);
			
		}

		// edit or add user
		
		if($id || $action === 'new') {

			MenuGenerator::setForceActiveMenu(true);

			return new Response(
				SimpleTemplate::create('admin/users_edit.php')
                    ->assign('user', $userRow)
                    ->display()
			);
		}

		// show list

		$users	= $db->doPreparedQuery("SELECT a.*, ag.alias FROM " . $db->quoteIdentifier('admin') . " a LEFT JOIN admingroups ag ON ag.admingroupsID = a.admingroupsID", []);

		return new Response(
			SimpleTemplate::create('admin/users_list.php')
				->assign('users', $users)
				->display()
		);
	}
	
	protected function getUserData()
    {

        $id = $this->request->query->get('id');
        $db = Application::getInstance()->getVxPDO();

        if ($id) {
            $formData = $db->doPreparedQuery("SELECT username as id, username, email, name, admingroupsid FROM " . $db->quoteIdentifier('admin') . " WHERE username = ?", [$id])->current();
        } else {
            $formData = null;
        }

        $adminGroups = $db->doPreparedQuery("SELECT admingroupsid, name FROM admingroups ORDER BY privilege_level");

        return new JsonResponse([
            'formData' => $formData,
            'options' => [
                'admingroups' => (array)$adminGroups
            ]
        ]);

    }

    protected function postUserData()
    {

        $request = new ParameterBag(json_decode($this->request->getContent(), true));
        $id = $request->get('id');
        $db = Application::getInstance()->getVxPDO();

        $form = HtmlForm::create()
            ->addElement(FormElementFactory::create('input', 'username', null, [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Benutzername ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create('input', 'email', null, [], [], true, ['trim', 'lowercase'], [new Email()], 'Ungültige E-Mail Adresse.'))
            ->addElement(FormElementFactory::create('input', 'name', null, [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Name ist ein Pflichtfeld.'))
            ->addElement(FormElementFactory::create('password', 'new_PWD', null, [], [], !$request->get('id'), [], [new RegularExpression('/^[^\s].{4,}[^\s]$/')], 'Das Passwort muss mindestens 4 Zeichen umfassen.'))
            ->addElement(FormElementFactory::create('password', 'new_PWD_verify', null))
            ->addElement(FormElementFactory::create('select', 'admingroupsid', null, [], [], true, [], [new RegularExpression(Rex::INT_EXCL_NULL)], 'Eine Benutzergruppe muss zugewiesen werden.'))
        ;

        $v = $form
            ->disableCsrfToken()
            ->bindRequestParameters($request)
            ->validate()
            ->getValidFormValues()
        ;

        $errors = $form->getFormErrors();

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

        if($id) {
            $userRow = $db->doPreparedQuery("SELECT * FROM " . $db->quoteIdentifier('admin') . " WHERE username = ?", [$id])->current();

            if (!$userRow) {
                return new JsonResponse('', Response::HTTP_FORBIDDEN);
            }
        }

        if(!isset($errors['email']) && (!$id || $v['email'] != strtolower($userRow['email'])) && !Util::isAvailableEmail($v['email'])) {
            $form->setError('email', null, 'Email wird bereits verwendet.');
        }
        if(!isset($errors['username']) && (!$id || $v['username'] !== $userRow['username']) && !Util::isAvailableUsername($v['username'])) {
            $form->setError('username', null, 'Username wird bereits verwendet.');
        }

        if(!($errors = $form->getFormErrors())) {

            try {

                if($id) {

                    $db->updateRecord('admin', ['username' => $id], $v->all());

                    return new JsonResponse([
                        'success' => true,
                        'user_id' => $v->get('username'),
                        'message' => 'Daten erfolgreich übernommen.'
                    ]);

                } else {

                    $db->insertRecord('admin', $v->all());

                    return new JsonResponse([
                        'success' => true,
                        'id' => $v->get('username'),
                        'message' => 'Daten erfolgreich übernommen.'
                    ]);
                }

            } catch (\Exception $e) {
                return new JsonResponse([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }

        }

        $response = [];

        foreach($errors as $element => $error) {
            $response[$element] = $error->getErrorMessage();
        }

        return new JsonResponse(['success' => false, 'errors' => $response, 'message' => 'Formulardaten unvollständig oder fehlerhaft.']);

    }

}
