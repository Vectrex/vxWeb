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

use vxPHP\Image\ImageModifier;
use vxPHP\Template\Filter\ImageCache;
use vxPHP\Controller\Controller;
use vxPHP\Http\JsonResponse;
use vxPHP\Application\Application;

/**
 *
 * @author Gregor Kofler
 *
 */
class FilesXhrController extends Controller {

	protected function execute() {

		if(($id = $this->request->request->get('folder'))) {
			$folder = MetaFolder::getInstance(NULL, $id);
		}
		else {
			$folder = MetaFolder::getInstance(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR) . FILES_PATH);
		}

		switch($this->request->request->get('httpRequest')) {

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

			// return file table for given folder id
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
						$this->getFolderTree(($id = $this->request->request->getInt('id')) ? MetaFile::getInstance(NULL, $id)->getMetafolder() : NULL)
					)
				);
				break;

			// return form for editing file
			case 'requestEditForm':
				if(($id = $this->request->request->getInt('id'))) {
					$markup = $this->getEditForm(MetaFile::getInstance(NULL, $id))->render();
					ImageCache::create()->apply($markup);
					$response = array('html' => $markup);
				}
				else {
					$response = array('error' => TRUE);
				}
				break;

			// check and update edit data
			case 'checkEditForm':
				$this->request->request->add($this->request->request->get('elements'));

				$file = MetaFile::getInstance(NULL, $this->request->request->getInt('id'));
				$form = $this->getEditForm($file);

				if(!($errors = $form
					->bindRequestParameters($this->request->request)
					->validate()
					->getFormErrors()
				)) {
					$file->setMetaData($form->getValidFormValues());

					$response = array(
						'folders'	=> $this->getFolderList($file->getMetafolder()),
						'files'		=> $this->getFileList($file->getMetafolder())
					);
				}

				else {
					$e = array();
					foreach($errors as $k => $v) {
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

				$this->request->request->add($this->request->request->get('elements'));

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
					foreach($errors as $k => $v) {
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

				$upload = FilesystemFile::uploadFile('File', $folder->getFilesystemFolder());

				if($upload === FALSE) {
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

				else if($upload === NULL) {
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

				$form = HtmlForm::create()
					->addElement(FormElementFactory::create('input', 'Title', '', array(), array(), FALSE, array('trim')))
					->addElement(FormElementFactory::create('input', 'Subtitle', '', array(), array(), FALSE, array('trim')))
					->addElement(FormElementFactory::create('input', 'customSort', '', array(), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT)))
					->addElement(FormElementFactory::create('input', 'referencedID', '', array(), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT_EXCL_NULL)))
					->addElement(FormElementFactory::create('input', 'referenced_Table', '', array(), array(), FALSE, array('trim')))
					->addElement(FormElementFactory::create('textarea', 'Description', ''))
					->addElement(FormElementFactory::create('checkbox', 'unpack_archives', 1));

				$values = $form->bindRequestParameters($this->request->request)->getValidFormValues();

				if(FALSE !== vxWeb\FileUtil::processFileUpload($folder, $upload, $values, isset($values['unpack_archives']))) {
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
		}

		return $this->addEchoToJsonResponse(new JsonResponse($response));
	}

	private function renameFile() {

		try {
			$file = MetaFile::getInstance(NULL, $this->request->request->get('id'));
			$file->rename(trim($this->request->request->get('filename')));

			$metaData = $file->getData();

			return array(
				'filename' => $file->getMetaFilename(),
				'elements' => array('html' => "<span title='{$metaData['Title']}'>{$file->getMetaFilename()}</span>"),
				'error' => FALSE
			);
		}
		catch(MetaFileException $e) {}

		return array('error' => TRUE);
	}

	private function delFile() {

		if(($id = $this->request->request->getInt('id'))) {
			$file = MetaFile::getInstance(NULL, $id);
			$folder = $file->getMetaFolder();
			$file->delete();

			return array(
				'folders'	=> $this->getFolderList($folder),
				'files'		=> $this->getFileList($folder)
			);
		}

		return array('error' => TRUE);
	}

	private function moveFile() {

		if(($id = $this->request->request->getInt('id'))) {
			try {

				$file = MetaFile::getInstance(NULL, $id);
				$folder = $file->getMetafolder();
				$file->move(MetaFolder::getInstance(NULL, $this->request->request->getInt('destination')));

				return array(
					'folders'	=> $this->getFolderList($folder),
					'files'		=> $this->getFileList($folder)
				);
			}
			catch(MetaFileException $e) {}
		}

		return array('error' => TRUE);
	}

	private function addFolder(MetaFolder $mf) {

		try {
			$mf->createFolder(preg_replace('~[^a-z0-9_-]~', '_', $this->request->request->get('folderName')));

			return array(
				'folders'	=> $this->getFolderList($mf),
				'files'		=> $this->getFileList($mf)
			);
		}
		catch(Exception $e) {}

		return array('error' => TRUE);
	}

	private function delFolder() {

		if(($id = $this->request->request->getInt('id'))) {

			$folder = MetaFolder::getInstance(NULL, $id);

			if(($parent = $folder->getParentMetafolder())) {

				$folder->delete();

				return array(
					'folders'	=> $this->getFolderList($parent),
					'files'		=> $this->getFileList($parent)
				);
			}
		}

		return array('error' => TRUE);
	}

	private function getFiles(MetaFolder $mf) {

		vxWeb\FileUtil::cleanupMetaFolder($mf);

		$folders	= $this->getFolderList($mf);
		$files		= $this->getFileList($mf);

		$pathSegments = array(array('name' => $mf->getName(), 'id' => $mf->getId()));

		while(($mf = $mf->getParentMetafolder())) {
			array_unshift($pathSegments, array('name' => $mf->getName(), 'id' => $mf->getId()));
		}

		return array(
			'pathSegments'	=> $pathSegments,
			'folders'		=> $folders,
			'files'			=> $files
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
			->addElement(FormElementFactory::create('input', 'referencedID', '', array('maxlength' => 5, 'class' => 's'), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT_EXCL_NULL)))
			->addElement(FormElementFactory::create('input', 'referenced_Table', '', array('maxlength' => 32, 'class' => 'm'), array(), FALSE, array('trim')))
			->addElement(FormElementFactory::create('input', 'File', '', array('type' => 'file')))
			->addElement(FormElementFactory::create('checkbox', 'unpack_archives', 1))
			->addElement(FormElementFactory::create('textarea', 'Description', '', array('rows' => 5, 'cols' => 40, 'class' => 'xl')))
			->addElement($addButton)
			->addElement($cancelButton);
	}

	private function getEditForm(MetaFile $file) {

		$data = $file->getData();

		if(($cacheInfo = $file->getFilesystemFile()->getCacheInfo())) {
			$cacheText = ", Cache: {$cacheInfo['count']} Files/gesamt ".number_format($cacheInfo['totalSize'] / 1024, 1, ',', '.').'kB';
		}
		else {
			$cacheText = '';
		}

		if(!preg_match('~^image/(png|gif|jpeg)$~', $file->getMimeType())) {
			$infoHtml = "<strong>{$data['File']}</strong> <em>({$file->getMimetype()}$cacheText)</em><br /><span class='smaller'><a href='/{$file->getRelativePath()}'>/{$file->getRelativePath()}</a></span>";
		}
		else {
			$infoHtml = "<strong>{$data['File']}</strong> <em>({$file->getMimetype()}$cacheText)</em><br /><span class='smaller'><a href='/{$file->getRelativePath()}'>/{$file->getRelativePath()}</a></span><br /><img class='thumb' src='".htmlspecialchars(str_replace(rtrim($_SERVER['DOCUMENT_ROOT'], '/'), '', $file->getPath()))."#resize 0 80' alt=''>";
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
			->addElement(FormElementFactory::create('input', 'referencedID', $file->getReferencedId(), array('maxlength' => 5, 'class' => 's'), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT_EXCL_NULL)))
			->addElement(FormElementFactory::create('input', 'referenced_Table', $file->getReferencedTable(), array('maxlength' => 32, 'class' => 'm'), array(), FALSE, array('trim')))
			->addElement($editButton)
			->addElement($cancelButton)
			->addMiscHtml('Fileinfo', $infoHtml)
			->addMiscHtml('Pathinfo', '/'.$file->getRelativePath())
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

	private function getFileList(MetaFolder $folder, array $columns = array('name', 'size', 'thumb', 'mTime', 'reference')) {

		$files = array();

		foreach(MetaFile::getMetaFilesInFolder($folder) as $f) {

			$isImage	= $f->isWebImage();
			$metaData	= $f->getData();
			$file		= array('columns' => array(), 'id' => $f->getId(), 'filename' => $f->getMetaFilename());

			foreach($columns as $c) {

				switch($c) {
					case 'name':
						$file['columns'][] = array('html' => "<span title='{$metaData['Title']}'>{$f->getMetaFilename()}</span>");
						break;

					case 'size':
						$file['columns'][] = number_format($f->getFileInfo()->getSize(), 0, ',', '.');
						break;

					case 'mTime':
						$file['columns'][] = date('Y-m-d H:i:s', $f->getFileInfo()->getMTime());
						break;

					case 'reference':
						if($f->getReferencedTable()) {
							$file['columns'][] = array('html' => "{$f->getReferencedTable()}<br />{$f->getReferencedId()}");
						}
						break;

					case 'thumb':
						if($isImage) {

							// check and - if required - generate thumbnail

							$fi			= $f->getFileInfo();
							$actions	= array('crop 1', 'resize 0 40');
							$dest		=
								$folder->getFilesystemFolder()->createCache().
								"{$fi->getFilename()}@".
								implode('|', $actions).
								'.'.
								pathinfo($fi->getFilename(), PATHINFO_EXTENSION);

							if(!file_exists($dest)) {
								$imgEdit = new ImageModifier($f->getFilesystemFile()->getPath());

								foreach($actions as $a) {
									$params = preg_split('~\s+~', $a);

									$method = array_shift($params);
									if(method_exists('vxPHP\\Image\\ImageModifier', $method)) {
										call_user_func_array(array($imgEdit, $method), $params);
									}
								}
								$imgEdit->export($dest);
							}

							$file['columns'][] = array('html' => '<img class="thumb" src="'.htmlspecialchars(str_replace(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR), '', $dest)).'" alt="" />');
						}

						else {
							$file['columns'][] = $f->getMimetype();
						}
				}


			}

			if(
				!is_null($this->request->query->get('filepicker')) && (
					is_null($this->request->query->get('filter')) ||
					$this->request->query->get('filter') != 'image' ||
					$isImage
				)) {
				$file['forward'] = array('filename' => '/'.$f->getRelativePath(), 'ckEditorFuncNum' => (int) $this->request->query->get('CKEditorFuncNum'));
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
