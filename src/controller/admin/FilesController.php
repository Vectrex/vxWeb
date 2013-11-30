<?php

use vxPHP\Template\SimpleTemplate;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;

/**
 *
 * @author Gregor Kofler
 *
 */
class FilesController extends Controller {

				/**
				 * @var \vxPHP\File\MetaFolder
				 */
	private		$currentFolder,

				$redirectQuery,

				/**
				 * @var Array
				 */
				$directoryBar;

	public function execute() {

		if($this->route->getRouteId() === 'filepicker') {
			return new Response(SimpleTemplate::create('admin/files_picker.php')->display());
		}

		return new Response(SimpleTemplate::create('admin/files_js.php')->display());
	}
}
