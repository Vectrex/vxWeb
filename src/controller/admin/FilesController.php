<?php

/* @TODO sanitize metadata */

use vxPHP\Util\Rex;

use vxPHP\File\MetaFile;
use vxPHP\File\MetaFolder;
use vxPHP\File\FilesystemFile;
use vxPHP\File\FilesystemFolder;
use vxPHP\File\Exception\MetaFileException;
use vxPHP\File\Exception\FilesystemFileException;
use vxPHP\File\Exception\MetaFolderException;
use vxPHP\File\Util;

use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\FormElement\ButtonElement;

use vxPHP\Image\ImageModifierFactory;

use vxPHP\Template\SimpleTemplate;
use vxPHP\Template\Filter\ImageCache;
use vxPHP\Template\Filter\AssetsPath;

use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;
use vxPHP\Application\Application;
use vxPHP\User\User;
use vxPHP\Orm\Custom\Article;

/**
 *
 * @author Gregor Kofler
 *
 */
class FilesController extends Controller {

	/**
	 * depending on route fill response with appropriate template
	 *
	 * (non-PHPdoc)
	 * @see \vxPHP\Controller\Controller::execute()
	 */
	protected function execute() {

		$uploadMaxFilesize = min(
			$this->toBytes(ini_get('upload_max_filesize')),
			$this->toBytes(ini_get('post_max_size'))
		);

		$maxExecutionTime = ini_get('max_execution_time');

		switch($this->route->getRouteId()) {
			case 'filepicker':
				$tpl = 'admin/files_picker.php';
				break;

			default:
				$tpl = 'admin/files_js.php';
		}
		
		return new Response(
			SimpleTemplate::create($tpl)
				->assign('upload_max_filesize',		$uploadMaxFilesize)
				->assign('max_execution_time_ms',	$maxExecutionTime * 900) // 10pct "safety margin"
				->display());
	}

	/**
	 * simple helper function to convert ini values like 10M or 256K to integer
	 * 
	 * @param string $val
	 */
	private function toBytes($val) {
		switch(strtolower(substr(trim($val),-1))) {
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
		return $val;
	}

	/**
	 * handle file upload via drag and drop
	 */
	protected function xhrUpload() {

		// get metafolder

		try {
			if(($id = $this->request->query->get('folder'))) {
				$folder = MetaFolder::getInstance(NULL, $id);
			}
			else {
				$folder = MetaFolder::getInstance(ltrim(FILES_PATH, '/'));
			}
		}

		catch(MetaFolderException $e) {
			return new JsonResponse(array ('error' => $e->getMessage()));
		}

		// get filename

		$filename = FilesystemFile::sanitizeFilename($this->request->headers->get('x-file-name'), $folder->getFilesystemFolder());
		file_put_contents($folder->getFilesystemFolder()->getPath() . $filename, file_get_contents('php://input'));

		
		// @todo better way to handle columns
		
		$fileColumns = array('name', 'size', 'mime', 'mTime');

		// link to article, when in "article" mode

		if($articlesId = $this->request->query->get('articlesId')) {

			try {
				$article = Article::getInstance($articlesId);
				$article->linkMetaFile(FilesystemFile::getInstance($folder->getFilesystemFolder()->getPath() . $filename)->createMetaFile());
				$article->save();
			}
			catch(\Exception $e) {
				return new JsonResponse(array ('error' => $e->getMessage()));
			}

			$fileColumns[] = 'linked';
		}
		
		return new JsonResponse(array('echo' => array('folder' => $id), 'response' => $this->getFiles($folder, $fileColumns)));
	}

	/**
	 * handle all other file actions: adding, deleting, moving, renaming...
	 */
	protected function xhrExecute() {

		try {
			if(($id = $this->request->request->get('folder'))) {
				$folder = MetaFolder::getInstance(NULL, $id);
			}
			else {
				$folder = MetaFolder::getInstance(ltrim(FILES_PATH, '/'));
			}
		}
		catch(MetaFolderException $e) {
			return new JsonResponse(array ('error' => $e->getMessage()));
		}

		switch($this->request->request->get('httpRequest')) {

			// link file to an article

			case 'linkToArticle':
				try {
					$article = Article::getInstance($this->request->request->getInt('articlesId'));
					$article->linkMetaFile(MetaFile::getInstance(NULL, $this->request->request->getInt('file')));
					$article->save();
					$response = array('error' => FALSE);
				}
				catch(\Exception $e) {
					$response = array('error' => $e->getMessage());
				}
				break;

			// unlink file from an article

			case 'unlinkFromArticle':
				try {
					$article = Article::getInstance($this->request->request->getInt('articlesId'));
					$article->unlinkMetaFile(MetaFile::getInstance(NULL, $this->request->request->getInt('file')));
					$article->save();
					$response = array('error' => FALSE);
				}
				catch(\Exception $e) {
					$response = array('error' => $e->getMessage());
				}
				break;

			// rename file

			case 'renameFile':
				$response = $this->renameFile();
				break;

			// create a new subdirectory

			case 'addFolder':
				$response = $this->addFolder($folder);
				break;

			// empty and delete a subdirectory

			case 'delFolder':
				$response = $this->delFolder();
				break;

			// move a file

			case 'moveFile':
				$response = $this->moveFile();
				break;

			// delete file and return new folder content

			case 'delFile':
				$response = $this->delFile();
				break;

			// return file table for given folder id with array containing available functions

			case 'getFiles':
				$response = $this->getFiles($folder);
				break;

			// return form for adding new file(delivered only once)

			case 'requestAddForm':
				$response = array('html' => $this->getAddForm()->render());
				break;

			// get complete folder tree

			case 'getFolderTree':
				$response = array(
					'branches' => array(
						$this->getFolderTree(($id = $this->request->request->getInt('file')) ? MetaFile::getInstance(NULL, $id)->getMetafolder() : NULL)
					)
				);
				break;

			// return form for editing file

			case 'requestEditForm':
				if(($id = $this->request->request->getInt('file'))) {
					$markup = $this->getEditForm(MetaFile::getInstance(NULL, $id))->render();

					ImageCache::create()->apply($markup);
					AssetsPath::create()->apply($markup);

					$response = array('html' => $markup);
				}
				else {
					$response = array('error' => TRUE);
				}
				break;

			// check and update edit data

			case 'checkEditForm':

				$file = MetaFile::getInstance(NULL, $this->request->request->getInt('file'));
				$form = $this->getEditForm($file);

				if(!($errors = $form
					->bindRequestParameters($this->request->request)
					->validate()
					->getFormErrors()
				)) {
					$file->setMetaData($form->getValidFormValues());
					$response = $this->getFiles($file->getMetafolder());
				}

				else {
					$e = array();
					foreach(array_keys($errors) as $k) {
						$e[] = array('name' => $k, 'error' => 1);
					}

					$response = array(
						'elements' => $e,
						'msgBoxes' => array(
							array(
								'id' => 'general',
								'elements' => array(array('html' => '<div class="errorBox">Eine oder mehrere Eingaben sind fehlerhaft!</div>'))
							)
						)
					);
				}
				break;

			// validate new file data before upload

			case 'checkUpload':

				$form = $this->getAddForm();

				if(!($errors = $this->getAddForm()
					->bindRequestParameters($this->request->request)
					->validate()
					->getFormErrors()
				)) {
					$response = array('command' => 'submit');
				}
				else {
					$e = array();
					foreach(array_keys($errors) as $k) {
						$e[] = array('name' => $k, 'error' => 1);
					}

					$response = array(
						'elements' => $e,
						'msgBoxes' => array(
							array(
								'id' => 'general',
								'elements' => array(array('html' => '<div class="errorBox">Eine oder mehrere Eingaben sind fehlerhaft!</div>'))
							)
						)
					);
				}
				break;

			// do upload

			case 'ifuSubmit':

				$upload = $this->request->files->get('File');

				// was a file uploaded at all?

				if($upload === NULL) {
					$response = array(
						'msgBoxes' => array(
							array(
								'id' => 'general',
								'elements' => array(
									array(
										'node' => 'div',
										'properties' => array('className' => 'errorBox'),
										'childnodes' => array(array('text' => 'Es wurde keine Datei zum Upload angegeben!'))
									)
								)
							)
						)
					);
					break;
				}

				// try to move uploaded file to its final destination

				try {
					$upload->move($folder->getFilesystemFolder());
				}
				
				catch(FilesystemFileException $e) {
					$response = array(
						'msgBoxes' => array(
							array(
								'id' => 'general',
								'elements' => array(
									array(
										'node' => 'div',
										'properties' => array('className' => 'errorBox'),
										'childnodes' => array(array('text' => 'Beim Upload der Datei ist ein Fehler aufgetreten!'))
									)
								)
							)
						)
					);
					break;
				}

				/*
				 * @todo
				 * Kludge: HtmlForm calls Request::createFromGlobals() which in turn parses $_FILES and throws an exception 
				 * since the uploaded file was already moved
				 */
				unset($_FILES['File']);

				$form = HtmlForm::create()
					->addElement(FormElementFactory::create('input', 'Title', '', array(), array(), FALSE, array('trim')))
					->addElement(FormElementFactory::create('input', 'Subtitle', '', array(), array(), FALSE, array('trim')))
					->addElement(FormElementFactory::create('input', 'customSort', '', array(), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT)))
					->addElement(FormElementFactory::create('textarea', 'Description', ''))
					->addElement(FormElementFactory::create('checkbox', 'unpack_archives', 1));

				$values = $form->bindRequestParameters($this->request->request)->getValidFormValues();

				// turn uploaded file into metafile, extract archive if neccessary

				$uploadedFiles = vxWeb\FileUtil::processFileUpload($folder, $upload, $values, isset($values['unpack_archives']));

				if(FALSE !== $uploadedFiles) {
					
					if($articlesId = $this->request->request->get('articlesId')) {
						
						try {
							$article = Article::getInstance($articlesId);
							
							foreach($uploadedFiles as $mf) {
								$article->linkMetaFile($mf);
								$article->save();
							}
						}
						catch(\Exception $e) {
							return new JsonResponse(array ('error' => $e->getMessage()));
						}
					}

					$response = array('success' => TRUE);
				}

				else {
					$response = array(
						'msgBoxes' => array(
							array(
								'id' => 'general',
								'elements' => array(
									array(
										'node' => 'div',
										'properties' => array('className' => 'errorBox'),
										'childnodes' => array(array('text' => 'Beim Upload der Datei ist ein Fehler aufgetreten!'))
									)
								)
							)
						)
					);
				}
				break;

			default:
				$response = null;
		}

		return $this->addEchoToJsonResponse(new JsonResponse($response));
	}

	private function renameFile() {

		try {
			$file = MetaFile::getInstance(NULL, $this->request->request->getInt('file'));
			$user = User::getSessionUser();

			if($user->hasSuperAdminPrivileges() || $user === $file->getCreatedBy()) {

				$file->rename(trim($this->request->request->get('filename')));

				$metaData = $file->getData();

				return array(
					'filename' => $file->getMetaFilename(),
					'elements' => array('html' => "<span title='{$metaData['Title']}'>{$file->getMetaFilename()}</span>"),
					'error' => FALSE
				);
			}
		}
		catch (MetaFileException $e) {
			return array('error' => $e->getMessage());
		}
	}

	private function delFile() {

		try {
			$file = MetaFile::getInstance(NULL, $this->request->request->getInt('file'));
			$user = User::getSessionUser();

			if($user->hasSuperAdminPrivileges() || $user === $file->getCreatedBy()) {

				$folder = $file->getMetaFolder();
				$file->delete();

				return $this->getFiles($folder);
			}
		}

		catch (MetaFileException $e) {
			return array('error' => $e->getMessage());
		}
	}

	private function moveFile() {

		try {

			$file = MetaFile::getInstance(NULL, $this->request->request->getInt('file'));
			$user = User::getSessionUser();

			if($user->hasSuperAdminPrivileges() || $user === $file->getCreatedBy()) {

				$folder = $file->getMetafolder();
				$file->move(MetaFolder::getInstance(NULL, $this->request->request->getInt('destination')));

				return $this->getFiles($folder);
			}
		}
		catch (\Exception $e) {
			return array('error' => $e->getMessage());
		}
	}

	private function addFolder(MetaFolder $folder) {

		try {
			$folder->createFolder(preg_replace('~[^a-z0-9_-]~', '_', $this->request->request->get('folderName')));
			return $this->getFiles($folder);
		}
		catch (\Exception $e) {
			return array('error' => $e->getMessage());
		}
	}

	private function delFolder() {

		try {
			if(($id = $this->request->request->getInt('del'))) {

				$folder = MetaFolder::getInstance(NULL, $id);

				if(($parent = $folder->getParentMetafolder())) {

					$folder->delete();
					return $this->getFiles($parent);
				}
			}
		}
		catch (\Exception $e) {
			return array('error' => $e->getMessage());
		}
	}

	private function getFiles(MetaFolder $mf, array $fileColumns = NULL) {
		
		vxWeb\FileUtil::cleanupMetaFolder($mf);

		if(!$fileColumns) {
			$fileColumns = $this->request->request->get('fileColumns', array('name', 'size', 'mime', 'mTime'));
		}

		$folders	= $this->getFolderList($mf);
		$files		= $this->getFileList($mf, $fileColumns);

		$pathSegments = array(array('name' => $mf->getName(), 'id' => $mf->getId()));

		while(($mf = $mf->getParentMetafolder())) {
			array_unshift($pathSegments, array('name' => $mf->getName(), 'id' => $mf->getId()));
		}

		switch($this->route->getRouteId()) {

			case 'filepickerXhr':
				$fileFunctions = array('rename', 'edit', 'move', 'del', 'forward');
				break;

			default:
				$fileFunctions = array('rename', 'edit', 'move', 'del');
		}

		return array(
			'pathSegments'	=> $pathSegments,
			'folders'		=> $folders,
			'files'			=> $files,
			'fileFunctions'	=> $fileFunctions
		);
	}

	private function getAddForm() {

		$addButton = new ButtonElement('submit_add', NULL, 'submit');
		$addButton->setInnerHTML('<span>Speichern</span>');

		$cancelButton = new ButtonElement('submit_cancel', NULL, 'submit');
		$cancelButton->setInnerHTML('<span>Abbrechen</span>');

		return HtmlForm::create('admin_file.htm')
			->initVar('add', 1)
			->setEncType('multipart/form-data')
			->setAttribute('class', 'editFileForm')
			->addElement(FormElementFactory::create('input', 'Title', '', array('maxlength' => 64, 'class' => 'xl'), array(), FALSE, array('trim')))
			->addElement(FormElementFactory::create('input', 'Subtitle', '', array('maxlength' => 64, 'class' => 'xl'), array(), FALSE, array('trim')))
			->addElement(FormElementFactory::create('input', 'customSort', '', array('maxlength' => 4, 'class' => 's'), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT)))
			->addElement(FormElementFactory::create('input', 'File', '', array('type' => 'file')))
			->addElement(FormElementFactory::create('checkbox', 'unpack_archives', 1))
			->addElement(FormElementFactory::create('textarea', 'Description', '', array('rows' => 5, 'cols' => 40, 'class' => 'xl')))
			->addElement($addButton)
			->addElement($cancelButton);
	}

	private function getEditForm(MetaFile $file) {

		$data		= $file->getData();
		$app		= Application::getInstance();
		$assetsPath	= !$app->hasNiceUris() ? ltrim($app->getRelativeAssetsPath(), '/') : '';

		if(($cacheInfo = $file->getFilesystemFile()->getCacheInfo())) {
			$cacheText = sprintf(', Cache: %d Files/gesamt %skB', $cacheInfo['count'], number_format($cacheInfo['totalSize'] / 1024, 1, ',', '.'));
		}
		else {
			$cacheText = '';
		}

		if(!preg_match('~^image/(png|gif|jpeg)$~', $file->getMimeType())) {
			$infoHtml = sprintf(
					'<strong>%s</strong> <em>(%s%s)</em><br /><span class="smaller"><a href="/%s" target="_blank">/%s%s</a></span>',
					$data['File'],
					$file->getMimetype(),
					$cacheText,
					$file->getRelativePath(),
					$assetsPath,
					$file->getRelativePath()
			);
		}
		else {
			$infoHtml = sprintf(
					'<strong>%s</strong> <em>(%s%s)</em><br /><span class="smaller"><a href="/%s" target="_blank">/%s%s</a></span><br /><img class="thumb" src="/%s#resize 0 80" alt="">',
					$data['File'],
					$file->getMimetype(),
					$cacheText,
					$file->getRelativePath(),
					$assetsPath,
					$file->getRelativePath(),
					$file->getRelativePath()
			);
		}

		$editButton = new ButtonElement('submit_edit', NULL, 'submit');
		$editButton->setInnerHTML('<span>Speichern</span>');

		$cancelButton = new ButtonElement('submit_cancel', NULL, 'submit');
		$cancelButton->setInnerHTML('<span>Abbrechen</span>');

		return HtmlForm::create('admin_file.htm')
			->setAttribute('class', 'editFileForm')
			->addElement(FormElementFactory::create('input', 'Title', NULL, array('maxlength' => 64, 'class' => 'xl'), array(), FALSE, array('trim')))
			->addElement(FormElementFactory::create('input', 'Subtitle', NULL, array('maxlength' => 64, 'class' => 'xl'), array(), FALSE, array('trim')))
			->addElement(FormElementFactory::create('input', 'customSort', NULL, array('maxlength' => 4, 'class' => 's'), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT)))
			->addElement(FormElementFactory::create('textarea', 'Description', NULL, array('rows' => 5, 'cols' => 40, 'class' => 'xl')))
			->addElement($editButton)
			->addElement($cancelButton)
			->addMiscHtml('Fileinfo', $infoHtml)
			->setInitFormValues($data);
	}

	private function getFolderList(MetaFolder $folder) {

		$folders	= array();

		foreach($folder->getMetaFolders() as $f) {
			$folders[] = array(
				'id'	=> $f->getId(),
				'name'	=> $f->getName()
			);
		}

		return $folders;
	}

	private function getFileList(MetaFolder $folder, array $columns) {

		$files		= array();
		$app		= Application::getInstance();
		$assetsPath	= !$app->hasNiceUris() ? ltrim($app->getRelativeAssetsPath(), '/') : '';

		if($articlesId = $this->request->query->get('articlesId', $this->request->request->get('articlesId'))) {
			$linkedFiles = Article::getInstance($articlesId)->getLinkedMetaFiles();
		}

		foreach(MetaFile::getMetaFilesInFolder($folder) as $f) {

			$isImage	= $f->isWebImage();
			$metaData	= $f->getData();
			$file		= array('columns' => array(), 'id' => $f->getId(), 'filename' => $f->getMetaFilename());

			foreach($columns as $c) {

				switch($c) {
					case 'name':
						$file['columns'][] = array('html' => sprintf('<span title="%s">%s</span>', $metaData['Title'], $f->getMetaFilename()));
						break;

					case 'size':
						$file['columns'][] = number_format($f->getFileInfo()->getSize(), 0, ',', '.');
						break;

					case 'mTime':
						$file['columns'][] = date('Y-m-d H:i:s', $f->getFileInfo()->getMTime());
						break;

					case 'mime':
						if($isImage) {

							// check and - if required - generate thumbnail

							$fi			= $f->getFileInfo();
							$actions	= array('crop 1', 'resize 0 40');
							$dest		=
								$folder->getFilesystemFolder()->createCache() .
								"{$fi->getFilename()}@" .
								implode('|', $actions) .
								'.' .
								pathinfo($fi->getFilename(), PATHINFO_EXTENSION);

							if(!file_exists($dest)) {
								$imgEdit = ImageModifierFactory::create($f->getFilesystemFile()->getPath());

								foreach($actions as $a) {
									$params = preg_split('~\s+~', $a);

									$method = array_shift($params);
									if(method_exists($imgEdit, $method)) {
										call_user_func_array(array($imgEdit, $method), $params);
									}
								}
								$imgEdit->export($dest);
							}

							$file['columns'][] = array('html' => '<img class="thumb" src="' . htmlspecialchars(str_replace(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR), '', $dest)) . '" alt="" />');
						}

						else {
							$file['columns'][] = $f->getMimetype();
						}
						break;

					case 'linked':
						if(isset($linkedFiles) && in_array($f, $linkedFiles, TRUE)) {
							$file['columns'][] = array('html' => '<input class="link" type="checkbox" checked="checked">');
							$file['linked'] = TRUE;
						}
						else {
							$file['columns'][] = array('html' => '<input class="link" type="checkbox">');
							$file['linked'] = FALSE;
						}
						break;
				}
			}

			if(
				$this->route->getRouteId() === 'filepickerXhr' && (
					is_null($this->request->query->get('filter')) ||
					$this->request->query->get('filter') != 'image' ||
					$isImage
				)) {
				$file['forward'] = array('filename' => '/' . $assetsPath . $f->getRelativePath(), 'ckEditorFuncNum' => (int) $this->request->query->get('CKEditorFuncNum'));
			}

			$files[] = $file;
		}

		return $files;
	}

	private function getFolderTree(MetaFolder $currentFolder = NULL) {

		$parseFolder = function(MetaFolder $f, $currentFolder) use (&$parseFolder) {

			$subTrees = $f->getMetaFolders();

			$branches = array();

			if(count($subTrees)) {
				foreach($subTrees as $s) {
					$branches[] = $parseFolder($s, $currentFolder);
				}
			}

			return array(
				'key'			=>	$f->getId(),
				'elements'		=>	array(
										'node' => 'span',
										'properties' => array('className' => $f === $currentFolder ? 'current' : ''),
										'childnodes' => array(array('text' => array_pop(explode(DIRECTORY_SEPARATOR, trim($f->getRelativePath(), DIRECTORY_SEPARATOR)))))
									),
				'terminates'	=>	!count($branches),
				'branches'		=>	$branches,
				'current'		=>	$f === $currentFolder,
				'path'			=>	$f->getRelativePath()
			);
		};

		$trees = array();

		foreach(MetaFolder::getRootFolders() as $f) {
			$trees[] = $parseFolder($f, $currentFolder);
		}

		return $trees[0];
	}
}
