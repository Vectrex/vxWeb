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

use vxPHP\Database\DatabaseInterface;
use vxPHP\Application\Application;
use vxPHP\User\Exception\UserException;
use vxPHP\Session\Session;
use vxPHP\User\UserProviderInterface;
use vxPHP\User\SessionUser;
use vxPHP\User\Role;
use vxPHP\User\UserInterface;
use vxPHP\User\SimpleSessionUserProvider;

/**
 * represents users within a vxWeb application, which are stored in the
 * session after initialization
 * 
 * @author Gregor Kofler, info@gregorkofler.com
 * @version 0.4.0, 2018-07-14
 *        
 */
class SessionUserProvider extends SimpleSessionUserProvider implements UserProviderInterface {
	
	/**
	 * @var DatabaseInterface
	 */
	private $db;
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \vxPHP\User\UserProviderInterface::refreshUser()
	 */
	public function refreshUser(UserInterface $user) {

	    $u = $this->getUserRow($user->getUsername());

		if(!$u) {
			throw new UserException(sprintf("User '%s' no longer exists.", $user->getUsername()));
		}

		$user
			->setHashedPassword($u['pwd'])
			->setRoles([new Role($u['group_alias'])])
			->replaceAttributes([
				'email' => $u['email'],
				'name' => $u['name'],
				'misc_data' => $u['misc_data'],
				'table_access' => $u['table_access'],
				'row_access' => $u['row_access'],
				'id' => $u['adminid'],
			])
		;

		return $user;

	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \vxPHP\User\UserProviderInterface::instanceUserByUsername()
	 */
	public function instanceUserByUsername($username) {

		$user = $this->getUserRow($username);

		if(!$user) {
			throw new UserException(sprintf("User '%s' not found or not unique.", $username));
		}

		$user = new SessionUser(
			$username,
			$user['pwd'],
			[
				new Role($user['group_alias'])
			],
			[
				'email' => $user['email'],
				'name' => $user['name'],
				'misc_data' => $user['misc_data'],
				'table_access' => $user['table_access'],
				'row_access' => $user['row_access'],
				'id' => $user['adminid'],
			]
		);
		
		return $user;

	}
	
	/**
	 * constructor
	 */
	public function __construct() {

		$this->db = Application::getInstance()->getDb();

	}

    /**
     * returns data row of user identified by $username
     * false if no user is found
     *
     * @param $username
     * @return array|bool
     */
	private function getUserRow($username) {

        $rows = $this->db->doPreparedQuery("
			SELECT
				a.*,
				ag.privilege_Level,
				ag.admingroupsID as groupid,
				LOWER(ag.alias) as group_alias
		
			FROM
				" . $this->db->quoteIdentifier('admin') . " a
				LEFT JOIN admingroups ag on a.admingroupsID = ag.admingroupsID
		
			WHERE
				username = ?", [$username]
        );

        if(count($rows) === 1) {
            return $rows->current();
        }

        return false;

    }

}