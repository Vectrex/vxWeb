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

use vxPHP\User\User;
use vxPHP\User\Exception\UserException;
use vxPHP\Application\Application;

/**
 * simple class to store utility methods
 *
 * @author Gregor Kofler
 * @version 1.4.0 2017-02-17
 */

class Util {

	/**
	 * check whether a user email is already assigned
	 *
	 * @param string $email
	 * @return boolean availability
	 */
	public static function isAvailableEmail($email) {

		return !count(Application::getInstance()->getDb()->doPreparedQuery('SELECT adminID FROM admin WHERE LOWER(email) = ?', array(strtolower($email))));

	}

	/**
	 * check whether a username is already assigned
	 *
	 * @param string $username
	 * @return boolean availability
	 */
	public static function isAvailableUsername($username) {
	
		return !count(Application::getInstance()->getDb()->doPreparedQuery('SELECT adminID FROM admin WHERE username = ?', array((string) $username)));
	
	}

}
