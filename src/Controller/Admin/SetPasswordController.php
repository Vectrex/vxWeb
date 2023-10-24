<?php

namespace App\Controller\Admin;

use vxPHP\Application\Application;
use vxPHP\Controller\Controller;
use vxPHP\Http\JsonResponse;
use vxPHP\Http\ParameterBag;
use vxPHP\Http\Response;
use vxPHP\Security\Password\PasswordEncrypter;

class SetPasswordController extends Controller
{
    protected function execute (): JsonResponse
    {
        $hash = $this->route->getPathParameter('hash');
        $bag = new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR));

        $pwd = $bag->get('password');
        $pwdRepeat = $bag->get('passwordRepeat');
        if (!$pwd || $pwd !== $pwdRepeat) {
            return new JsonResponse(['success' => false, 'message' => 'Ungültiges Passwort.']);
        }
        $pdo = Application::getInstance()->getVxPDO();
        $row = $pdo->doPreparedQuery("SELECT adminid FROM admin WHERE temporary_hash = ?", [$hash])->current();
        if (!$row) {
            return new JsonResponse(['success' => false, 'message' => 'Ungültiger Code.']);
        }
        $pdo->updateRecord('admin', $row['adminid'], [
            'pwd' => (new PasswordEncrypter())->hashPassword($pwd),
            'temporary_hash' => null
        ]);
        return new JsonResponse(['success' => true, 'message' => 'Passwort aktualisiert.']);
    }
}