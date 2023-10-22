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
        try {
            $origin = $this->request->server->get('HTTP_ORIGIN');
            $bag = new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR));
            $receiver = $bag->get('email');

            if (is_null(Application::getInstance()->getConfig()->mail->mailer)) {
                return new JsonResponse(['success' => false, 'message' => 'Keine Mailingfunktion konfiguriert.']);
            }
            if (($user = Application::getInstance()->getCurrentUser()) && $user->getAttribute('email') === $receiver) {
                return new JsonResponse(['success' => false, 'message' => 'Bereits eingeloggt.']);
            }

            // sleep one to two secs

            $hash = strtr(base64_encode(random_bytes(32)), '+/=', '._-');
            $pdo = Application::getInstance()->getVxPDO();
            $row = $pdo->doPreparedQuery("SELECT adminid FROM admin WHERE email = ?", [$receiver])->current();

            if (!$row) {
                usleep(random_int(500, 1500) * 1000);
            } else {
                $pdo->updateRecord('admin', $row['adminid'], ['temporary_hash' => $hash]);
                $url = $origin . '/reset-password/' . $hash;
                (new Email())
                    ->setReceiver($receiver)
                    ->setSubject('Passwort zurücksetzen')
                    ->setMailText(sprintf(self::MAILTEXT, $url))
                    ->send();
            }

            return new JsonResponse(['success' => true, 'message' => 'Falls die E-Mail Adresse zugeordnet werden konnte wurde an diese eine Link zum Neusetzen des Passwortes geschickt.']);
        }
        catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'exception' => $e]);
        }
    }
}
