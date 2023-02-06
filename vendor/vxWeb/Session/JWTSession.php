<?php

namespace vxWeb\Session;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use vxPHP\Application\Application;
use vxPHP\Application\Config\DotEnvReader;
use vxPHP\Application\Exception\ApplicationException;
use vxPHP\Http\Request;
use vxPHP\User\User;

/**
 * basic functions to transfer a session id via a JWT
 */
class JWTSession
{
    /**
     * retrieve a payload (presumably a session id) from a JWT
     *
     * @param Request $request
     * @return false|string
     */
    public static function getId (Request $request): false | string
    {
        $encodedToken = preg_replace('/^bearer\s+/i', '', $request->headers->get('Authorization', ''));

        if ($encodedToken) {
            try {
                (new DotEnvReader(Application::getInstance()->getRootPath() . '.env'))->read();

                $jwt = JWT::decode($encodedToken, new Key(getenv('jwtsecret'), 'HS512'));
                return $jwt->payload ?: false;
            } catch (\Exception $e) {}
        }
        return false;
    }

    /**
     * create a new bearer token holding the session id
     *
     * @return string
     * @throws ApplicationException
     */
    public static function createToken (): string
    {
        (new DotEnvReader(Application::getInstance()->getRootPath() . '.env'))->read();
        $key = getenv('jwtsecret');
        $issuedAt = new \DateTimeImmutable();
        $expire = $issuedAt->modify('+12 hours')->getTimestamp();

        // get session user credentials

        return JWT::encode([
            'iat' => $issuedAt->getTimestamp(),                 // Issued at: time when the token was generated
            'iss' => Request::createFromGlobals()->getHost(),   // Issuer
            'nbf' => $issuedAt->getTimestamp(),                 // Not before
            'exp' => $expire,                                   // Expire
            'payload' => session_id(),
        ], $key, 'HS512');
    }
    /**
     * refresh a JWT if expiration is due within an hour
     *
     * @param $encodedToken
     * @return string|null
     * @throws ApplicationException
     */
    public static function refreshToken ($encodedToken): ?string
    {
        (new DotEnvReader(Application::getInstance()->getRootPath() . '.env'))->read();

        $jwt = JWT::decode($encodedToken, new Key(getenv('jwtsecret'), 'HS512'));

        if ($jwt->exp < (new \DateTimeImmutable())->modify('+1 hours')->getTimestamp()) {
            return self::createToken();
        }

        return null;
    }
}