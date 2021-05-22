<?php
/*
 * This file is part of the vxPHP/vxWeb framework
 *
 * (c) Gregor Kofler <info@gregorkofler.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace vxWeb\Model\MetaFile;

use vxPHP\Observer\Event;

class MetaFileEvent extends Event
{
	public const AFTER_METAFILE_CREATE = 'FileEvent.afterMetafileCreate';
	public const BEFORE_METAFILE_DELETE	= 'FileEvent.beforeMetafileDelete';
}