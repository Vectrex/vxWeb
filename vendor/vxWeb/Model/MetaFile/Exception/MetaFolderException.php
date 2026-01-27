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
	public const int METAFOLDER_DOES_NOT_EXIST = 1;
	public const int METAFOLDER_ALREADY_EXISTS = 2;
	public const int ID_OR_PATH_REQUIRED = 3;
	public const int NO_ROOT_FOLDER_FOUND = 4;
}
