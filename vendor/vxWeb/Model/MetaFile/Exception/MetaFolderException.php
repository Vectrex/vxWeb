<?php
/*
 * This file is part of the vxPHP/vxWeb framework
 *
 * (c) Gregor Kofler <info@gregorkofler.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace vxWeb\Model\MetaFile\Exception;

class MetaFolderException extends \Exception
{
	public const METAFOLDER_DOES_NOT_EXIST = 1;
	public const METAFOLDER_ALREADY_EXISTS = 2;
	public const ID_OR_PATH_REQUIRED = 3;
	public const NO_ROOT_FOLDER_FOUND = 4;
}
