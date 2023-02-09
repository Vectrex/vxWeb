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

use vxPHP\User\RoleHierarchy;

/**
 * Represents the custom role hierarchy used with vxWeb
 *
 * @author Gregor Kofler, info@gregorkofler.com
 * @version 0.1.2 2022-02-08
 */
class vxWebRoleHierarchy extends RoleHierarchy
{
	public const AUTH_SUPERADMIN = 1;
    public const AUTH_PRIVILEGED = 16;
    public const AUTH_OBSERVE_TABLE = 256;
    public const AUTH_OBSERVE_ROW = 4096;
	
	private array $roles = [
		'superadmin' => [
			'privileged'
		],
		'privileged' => [
			'observe_table'
		],
		'observe_table' => [
			'observe_row'
		]
	];
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \vxPHP\User\RoleHierarchy::__construct()
	 */
	public function __construct()
    {
		parent::__construct ($this->roles);
	}
}