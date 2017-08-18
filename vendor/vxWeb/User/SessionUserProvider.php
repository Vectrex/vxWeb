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
 * @version 0.3.1, 2017-08-18
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

		$rows = $this->db->doPreparedQuery("
			SELECT
				a.*,
				ag.privilege_Level,
				ag.admingroupsID as groupid,
				LOWER(ag.alias) as group_alias
		
			FROM
				admin a
				LEFT JOIN admingroups ag on a.admingroupsID = ag.admingroupsID
		
			WHERE
				username = ?", [$user->getUsername()]
				);
		
		if(count($rows) !== 1) {
			throw new UserException(sprintf("User '%s' no longer exists.", $user->getUsername()));
		}

		$user
			->setHashedPassword($rows[0]['pwd'])
			->setRoles([new Role($rows[0]['group_alias'])])
			->replaceAttributes([
				'email' => $rows[0]['email'],
				'name' => $rows[0]['name'],
				'misc_data' => $rows[0]['misc_data'],
				'table_access' => $rows[0]['table_access'],
				'row_access' => $rows[0]['row_access'],
				'id' => $rows[0]['adminid'],
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

		$rows = $this->db->doPreparedQuery("
			SELECT
				a.*,
				ag.privilege_Level,
				ag.admingroupsID as groupid,
				LOWER(ag.alias) as group_alias
		
			FROM
				admin a
				LEFT JOIN admingroups ag on a.admingroupsID = ag.admingroupsID
		
			WHERE
				username = ?", [$username]
		);
		
		if(count($rows) !== 1) {
			throw new UserException(sprintf("User '%s' not found or not unique.", $username));
		}
		
		$user = new SessionUser(
			$username,
			$rows[0]['pwd'],
			[
				new Role($rows[0]['group_alias'])
			],
			[
				'email' => $rows[0]['email'],
				'name' => $rows[0]['name'],
				'misc_data' => $rows[0]['misc_data'],
				'table_access' => $rows[0]['table_access'],
				'row_access' => $rows[0]['row_access'],
				'id' => $rows[0]['adminid'],
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

}