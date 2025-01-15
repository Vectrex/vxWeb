<?php
/*
 * This file is part of the vxPHP/vxWeb framework
 *
 * (c) Gregor Kofler <info@gregorkofler.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vxWeb\User;

use vxPHP\Application\Exception\ApplicationException;
use vxPHP\Application\Application;
use vxPHP\Application\Exception\ConfigException;

/**
 * simple class to store utility methods
 *
 * @author Gregor Kofler
 * @version 1.4.2 2023-06-02
 */
class Util
{
    /**
     * check whether a user email is already assigned
     *
     * @param string $email
     * @return boolean availability
     * @throws ApplicationException|ConfigException
     */
    public static function isAvailableEmail(string $email): bool
    {
        $db = Application::getInstance()->getVxPDO();
        return !($db->doPreparedQuery('SELECT adminID FROM ' . $db->quoteIdentifier('admin') . ' WHERE LOWER(email) = ?', [strtolower($email)])->count());
    }

    /**
     * check whether a username is already assigned
     *
     * @param string $username
     * @return boolean availability
     * @throws ApplicationException|ConfigException
     */
    public static function isAvailableUsername(string $username): bool
    {
        $db = Application::getInstance()->getVxPDO();
        return !($db->doPreparedQuery('SELECT adminID FROM ' . $db->quoteIdentifier('admin') . ' WHERE username = ?', [$username])->count());
    }
}
