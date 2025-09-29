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

use vxPHP\Application\Application;
use vxPHP\Database\DatabaseInterface;
use vxPHP\Database\RecordsetIteratorInterface;
use vxPHP\User\Exception\UserException;
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
 * @version 0.5.4, 2025-09-29
 *
 */
class SessionUserProvider extends SimpleSessionUserProvider
{
    /**
     * @var DatabaseInterface
     */
    private DatabaseInterface $vxPDO;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->vxPDO = Application::getInstance()->getVxPDO();
    }

    /**
     * {@inheritdoc}
     *
     * @see UserProviderInterface::refreshUser
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        $u = $this->getUserRows($user->getUsername())->current();

        if (!$u) {
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
            ]);

        return $user;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see UserProviderInterface::instanceUserByUsername
     */
    public function instanceUserByUsername($username): SessionUser
    {
        $rows = $this->getUserRows($username);

        if (count($rows) !== 1) {
            throw new UserException(sprintf("User identified by username '%s' not found or not unique.", $username));
        }

        return $this->createUser($rows->current());
    }

    /**
     * @param string $email
     * @return SessionUser
     * @throws UserException
     */
    public function instanceUserByEmail(string $email): SessionUser
    {
        $rows = $this->getUserRows($email, 'email');

        if (count($rows) !== 1) {
            throw new UserException(sprintf("User identified by e-mail '%s' not found or not unique.", $email));
        }

        return $this->createUser($rows->current());
    }

    /**
     * returns a data row of user identified by $value in $column
     * false if no user is found
     *
     * @param string $value
     * @param string $column
     * @return RecordsetIteratorInterface
     */
    private function getUserRows(string $value, string $column = 'username'): RecordsetIteratorInterface
    {
        return $this->vxPDO->doPreparedQuery(sprintf("
			SELECT
				a.*,
				ag.privilege_Level,
				ag.admingroupsID as groupid,
				LOWER(ag.alias) as group_alias
			FROM
				" . $this->vxPDO->quoteIdentifier('admin') . " a
				LEFT JOIN admingroups ag on a.admingroupsID = ag.admingroupsID
		
			WHERE
				%s = ?", $this->vxPDO->quoteIdentifier($column)), [$value]
        );
    }

    /**
     * @param array $row
     * @return SessionUser
     * @throws \Exception
     */
    private function createUser(array $row): SessionUser
    {
        return new SessionUser(
            $row['username'],
            $row['pwd'],
            [
                new Role($row['group_alias'])
            ],
            [
                'email' => $row['email'],
                'name' => $row['name'],
                'misc_data' => $row['misc_data'],
                'table_access' => $row['table_access'],
                'row_access' => $row['row_access'],
                'id' => $row['adminid'],
                'last_login' => $row['last_login'],
            ]
        );
    }
}