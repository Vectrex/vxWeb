<?php

namespace App\Controller\Admin;

use vxPHP\Application\Application;
use vxPHP\Controller\Controller;
use vxPHP\Http\JsonResponse;
use vxPHP\Http\ParameterBag;
use vxPHP\Mail\Email;

class RequestPasswordController extends Controller
{
    protected const MAILTEXT = <<<'EOD'
URL %s
EOD;

    protected function execute(): JsonResponse
    {
        $origin = $this->request->server->get('HTTP_ORIGIN');
        $bag = new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        $receiver = $bag->get('email');
        $email = new Email();
        if (!$email->getMailer()) {
            return new JsonResponse(['success' => false, 'message' => 'Keine Mailingfunktion konfiguriert.']);
        }
        if (($user = Application::getInstance()->getCurrentUser()) && $user->getAttribute('email') === $email) {
            return new JsonResponse(['success' => false, 'message' => 'Bereits eingeloggt.']);
        }

        // sleep one to two secs

        $hash = strstr(base64_encode(random_bytes(32)), '+/=', '._-');
        $pdo = Application::getInstance()->getVxPDO();
        $row = $pdo->doPreparedQuery("SELECT adminid FROM admin WHERE email = ?", [$receiver])->current();

        if (!$row) {
            usleep(1e6 + random_int(0, 1000) * 1000);
        }
        else {
            $pdo->updateRecord('admin', $row['adminid'], ['temporary_hash' => $hash]);
            $url = $origin . '/reset-password/' . $hash;
            $email
                ->setReceiver($receiver)
                ->setSubject('Passwort zurücksetzen')
                ->setMailText(sprintf(self::MAILTEXT, $url))
                ->send()
            ;
        }

        return new JsonResponse(['success' => true, 'message' => 'Falls die E-Mail Adresse zugeordnet werden konnte wurde an diese eine Link zum Neusetzen des Passwortes geschickt.']);
    }
}
