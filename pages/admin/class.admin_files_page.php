<?php
/* @TODO sanitize metadata */

use vxPHP\Util\Rex;
use vxPHP\Util\Uuid;

use vxPHP\File\MetaFile;
use vxPHP\File\MetaFolder;
use vxPHP\File\FilesystemFile;
use vxPHP\File\FilesystemFolder;
use vxPHP\File\Exception\MetaFileException;
use vxPHP\File\Exception\FilesystemFileException;
use vxPHP\File\Exception\MetaFolderException;
use vxPHP\File\Util;

use vxPHP\Template\SimpleTemplate;

use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\FormElement\ButtonElement;

use vxPHP\Image\ImageModifier;

/**
 *
 * @author Gregor Kofler
 *
 */
class admin_files_page extends page {

				/**
				 * @var \vxPHP\File\MetaFolder
				 */
	private		$currentFolder,

				$redirectTo,

				/**
				 * @var Array
				 */
				$directoryBar;

	public function __construct() {

		parent::__construct();

		if(is_null($this->request->query->get('force')) || $this->currentDocument == 'embedded.php') {
			return;
		}

		// get folders and files
		$this->getFoldersAndFiles();

		// set target for redirect after successful operation
		$this->redirectTo = "files?folder={$this->currentFolder->getId()}&force=htmlonly";

		// delete file
		if(end($this->pathSegments) === 'del' && is_numeric(($id = $this->request->query->get('file')))) {
			MetaFile::getInstance(NULL, $id)->delete();
			$this->redirect($this->redirectTo);
		}

		// edit file
		if(end($this->pathSegments) === 'edit' && is_numeric(($id = $this->request->query->get('file')))) {

			// cacheinfo not used yet

			$file = MetaFile::getInstance(NULL, $id);
			$form = $this->getEditForm($file);

			$form->bindRequestParameters();

			if($form->wasSubmittedByName('submit_cancel')) {
				$this->redirect($this->redirectTo);
			}
			if($form->wasSubmittedByName('submit_edit')) {

				$form->validate();

				if(! $form->getFormErrors()) {
					try {
						$file->setMetaData($form->getValidFormValues());
						$this->redirect($this->redirectTo);
					} catch(Exception $e) {
						$form->setError('system');
					}
				}
				else {
					$form->setError('general');
				}
			}
			$this->data['form'] = $form->render();
		}

		// add file

		if(end($this->pathSegments) === 'add') {

			$form = $this->getAddForm();
			$form->bindRequestParameters();

			if($form->wasSubmittedByName('submit_cancel')) {
				$this->redirect($this->redirectTo);
			}

			if($form->wasSubmittedByName('submit_add')) {

				$form->validate();

				if(! $form->getFormErrors()) {

					$upload = FilesystemFile::uploadFile('File', $this->currentFolder->getFilesystemFolder());

					if($upload === FALSE) {
						$form->setError('system');
					}
					else if($upload === NULL) {
						$form->setError('file');
						$form->setError('general');
					}

					if(! $form->getFormErrors() && $this->processFileUpload($form, $upload)) {
						$this->redirect($this->redirectTo);
					}
					else {
						$form->setError('system');
					}
				}

				else {
					$form->setError('general');
				}
			}

			$this->data['form'] = $form->render();
		}
	}

	public function content() {
		if($this->currentDocument == 'embedded.php') {
			$tpl = new SimpleTemplate('admin_files.htm');
			$tpl->assign('ckEditorCallbackParam', $this->request->query->get('CKEditorFuncNum'));
		}

		else if(is_null($this->request->query->get('force'))) {
			$tpl = new SimpleTemplate('admin_files_js.htm');
		}

		else {
			$tpl = new SimpleTemplate('admin_files.htm');

			$tpl->assign('directoryBar', $this->directoryBar);
			$tpl->assign('filesTable', $this->getFileTableHtml($this->currentFolder, $this->request->query->get('file')));
			$tpl->assign('folderID', $this->currentFolder->getId());
			$tpl->assign('folderName', $this->currentFolder->getName());

			if(isset($this->data['form'])) {
				$tpl->assign('form', $this->data['form']);
			}
		}

		$html = $tpl->display();

		$this->html .= $html;
		return $html;
	}

	/**
	 * return markup of files table
	 * @param Metafolder $folder folder which content is returned
	 * @param $fileId id of edited file
	 */
	private function getFileTableHtml(Metafolder $folder, $fileId = NULL) {

		$tpl = new SimpleTemplate('admin_files_filestable.htm');
		$files = MetaFile::getMetaFilesInFolder($folder);
		$folders = $folder->getMetafolders();

		usort($folders, function($a, $b) { return strcasecmp($a->getFullPath(), $b->getFullPath()); });
		usort($files, function($a, $b) { return strcasecmp($a->getMetaFilename(), $b->getMetaFilename()); });

		$tpl->assign('currentFolder', $folder);
		$tpl->assign('files', $files);
		$tpl->assign('folders', $folders);
		$tpl->assign('filter', $this->request->query->get('filter'));
		$tpl->assign('editing', $fileId);

		return $tpl->display();
	}

	private function getAddForm() {

		$form = new HtmlForm('admin_file.htm');
		$form->setEncType('multipart/form-data');
		$form->setAttribute('class', 'editFileForm');

		$form->initVar('add', 1);

		$form->addElement(FormElementFactory::create('input', 'Title', '', array('maxlength' => 64, 'class' => 'xl'), array(), FALSE, array('trim')));
		$form->addElement(FormElementFactory::create('input', 'Subtitle', '', array('maxlength' => 64, 'class' => 'xl'), array(), FALSE, array('trim')));
		$form->addElement(FormElementFactory::create('input', 'customSort', '', array('maxlength' => 4, 'class' => 's'), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT)));
		$form->addElement(FormElementFactory::create('input', 'referencedID', '', array('maxlength' => 5, 'class' => 's'), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT_EXCL_NULL)));
		$form->addElement(FormElementFactory::create('input', 'referenced_Table', '', array('maxlength' => 32, 'class' => 'm'), array(), FALSE, array('trim')));
		$form->addElement(FormElementFactory::create('input', 'File', '', array('type' => 'file')));
		$form->addElement(FormElementFactory::create('checkbox', 'unpack_archives', 1));
		$form->addElement(FormElementFactory::create('textarea', 'Description', '', array('rows' => 5, 'cols' => 40, 'class' => 'xl')));

		$addButton = new ButtonElement('submit_add', NULL, 'submit');
		$addButton->setInnerHTML('<span>Speichern</span>');
		$form->addElement($addButton);
		$cancelButton = new ButtonElement('submit_cancel', NULL, 'submit');
		$cancelButton->setInnerHTML('<span>Abbrechen</span>');
		$form->addElement($cancelButton);

		return $form;
	}

	private function getEditForm(MetaFile $file) {

		$data = $file->getData();

		$form = new HtmlForm('admin_file.htm');
		$form->setAttribute('class', 'editFileForm');
		$form->setInitFormValues($data);

		$form->addElement(FormElementFactory::create('input', 'Title', NULL, array('maxlength' => 64, 'class' => 'xl'), array(), FALSE, array('trim')));
		$form->addElement(FormElementFactory::create('input', 'Subtitle', NULL, array('maxlength' => 64, 'class' => 'xl'), array(), FALSE, array('trim')));
		$form->addElement(FormElementFactory::create('input', 'customSort', NULL, array('maxlength' => 4, 'class' => 's'), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT)));
		$form->addElement(FormElementFactory::create('textarea', 'Description', NULL, array('rows' => 5, 'cols' => 40, 'class' => 'xl')));
		$form->addElement(FormElementFactory::create('input', 'referencedID', $file->getReferencedId(), array('maxlength' => 5, 'class' => 's'), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT_EXCL_NULL)));
		$form->addElement(FormElementFactory::create('input', 'referenced_Table', $file->getReferencedTable(), array('maxlength' => 32, 'class' => 'm'), array(), FALSE, array('trim')));

		if(($cacheInfo = $file->getFilesystemFile()->getCacheInfo())) {
			$cacheText = ", Cache: {$cacheInfo['count']} Files/gesamt ".number_format($cacheInfo['totalSize'] / 1024, 1, ',', '.').'kB';
		}
		else {
			$cacheText = '';
		}

		$editButton = new ButtonElement('submit_edit', NULL, 'submit');
		$editButton->setInnerHTML('<span>Speichern</span>');
		$form->addElement($editButton);
		$cancelButton = new ButtonElement('submit_cancel', NULL, 'submit');
		$cancelButton->setInnerHTML('<span>Abbrechen</span>');
		$form->addElement($cancelButton);

		if(!preg_match('~^image/(png|gif|jpeg)$~', $file->getMimeType())) {
			$infoHtml = "<strong>{$data['File']}</strong> <em>({$file->getMimetype()}$cacheText)</em><br /><span class='smaller'><a href='/{$file->getRelativePath()}'>/{$file->getRelativePath()}</a></span>";
		}
		else {
			$infoHtml = "<strong>{$data['File']}</strong> <em>({$file->getMimetype()}$cacheText)</em><br /><span class='smaller'><a href='/{$file->getRelativePath()}'>/{$file->getRelativePath()}</a></span><br /><img class='thumb' src='".htmlspecialchars(str_replace(rtrim($_SERVER['DOCUMENT_ROOT'], '/'), '', $file->getPath()))."#resize 0 80' alt=''>";
		}

		$form->addMiscHtml('Fileinfo', $infoHtml);
		$form->addMiscHtml('Pathinfo', '/'.$file->getRelativePath());

		return $form;
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

			if(
				$this->currentDocument === 'embedded.php' && (
					!isset($this->config->_get['filter']) ||
					!$this->config->_get['filter'] == 'image' ||
					$isImage
				)) {
					$file['forward'] = array('filename' => '/'.$f->getRelativePath(), 'ckEditorFuncNum' => (int) $this->request->query->get('CKEditorFuncNum'));
				}
			}

			$files[] = $file;
		}

		return $files;
	}

	protected function handleHttpRequest() {

		if(($id = $this->request->request->get('folder'))) {
			$folder = MetaFolder::getInstance(NULL, $id);
		}
		else {
			$folder = MetaFolder::getInstance(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR) . FILES_PATH);
		}

		switch($this->request->request->get('httpRequest')) {

			// rename file

			case 'renameFile':
				try {
					$file = MetaFile::getInstance(NULL, $this->request->request->get('id'));
					$file->rename(trim($this->request->request->get('filename')));
				}
				catch(MetaFileException $e) {
					return array('error' => TRUE);
				}
				$metaData = $file->getData();

				return array(
					'filename' => $file->getMetaFilename(),
					'elements' => array('html' => "<span title='{$metaData['Title']}'>{$file->getMetaFilename()}</span>"),
					'error' => FALSE
				);

				break;

			// create a new subdirectory

			case 'addFolder':
				try {
					$folder->createFolder(preg_replace('~[^a-z0-9_-]~', '_', $this->request->request->get('folderName')));
				}
				catch(Exception $e) {
					return array('error' => TRUE);
				}

				return array(
					'folders'	=> $this->getFolderList($folder),
					'files'		=> $this->getFileList($folder)
				);

			// empty and delete a subdirectory

			case 'delFolder':
				if(is_numeric($this->request->request->get('id'))) {

					$folder = MetaFolder::getInstance(NULL, $this->request->request->get('id'));

					if(($parent = $folder->getParentMetafolder())) {
						$folder->delete();

						return array(
							'folders'	=> $this->getFolderList($parent),
							'files'		=> $this->getFileList($parent)
						);
					}
				}
				break;

			// move a file

			case 'moveFile':
				if(is_numeric($this->request->request->get('id'))) {
					try {

						$file = MetaFile::getInstance(NULL, $this->request->request->get('id'));
						$folder = $file->getMetafolder();
						$file->move(MetaFolder::getInstance(NULL, $this->request->request->get('destination')));

						return array(
								'folders'	=> $this->getFolderList($folder),
								'files'		=> $this->getFileList($folder)
						);
					}
					catch(MetaFileException $e) {
					}
				}
				break;

			// return file table for given folder id

			case 'getFiles':
				$this->cleanUpFolder($folder);

				$files		= $this->getFileList($folder);
				$folders	= $this->getFolderList($folder);

				$pathSegments = array(array('name' => $folder->getName(), 'id' => $folder->getId()));

				while(($folder = $folder->getParentMetafolder())) {
					array_unshift($pathSegments, array('name' => $folder->getName(), 'id' => $folder->getId()));
				}

				return array(
					'pathSegments'	=> $pathSegments,
					'folders'		=> $folders,
					'files'			=> $files
				);

			// delete file and return new folder content

			case 'delFile':
				if(is_numeric($this->request->request->get('id'))) {
					$file = MetaFile::getInstance(NULL, $this->request->request->get('id'));
					$file->delete();

					return array(
						'folders'	=> $this->getFolderList($folder),
						'files'		=> $this->getFileList($folder)
					);
				}
				break;

			// return form for adding new file(delivered only once)

			case 'requestAddForm':
				return array(array('html' => $this->getAddForm()->render()));

			// return form for editing file

			case 'requestEditForm':
				if(is_numeric($this->request->request->get('id'))) {
					$markup = $this->getEditForm(MetaFile::getInstance(NULL, $this->request->request->get('id')))->render();
					SimpleTemplate::parseImageCaches($markup);
					return array(array('html' => $markup));
				}
				break;

			// check and update edit data

			case 'checkEditForm':
				$_POST = $this->validatedRequests['elements'];

				$file = MetaFile::getInstance(NULL, $this->validatedRequests['elements']['id']);

				$form = $this->getEditForm($file);
				$form->validate();

				if(!($errors = $form->getFormErrors())) {
					$file->setMetaData($this->validatedRequests['elements']);

					return array(
						'folders'	=> $this->getFolderList($file->getMetafolder()),
						'files'		=> $this->getFileList($file->getMetafolder())
					);
				}

				else {
					$e = array();
					foreach($errors as $k => $v) {
						$e[] = array('name' => $k, 'error' => 1);
					}

					return array('elements' => $e, 'msgBoxes' => array(array('id' => 'general', 'elements' => array(array('html' => '<div class="errorBox">Eine oder mehrere Eingaben sind fehlerhaft!</div>')))));
				}

				// validate new file data before upload

				case 'checkUpload':
					$_POST = $this->validatedRequests['elements'];

					$form = $this->getAddForm();
					$form->validate();

					if(!($errors = $form->getFormErrors())) {
						return array('command' => 'submit');
					}
					else {
						$e = array();
						foreach($errors as $k => $v) {
							$e[] = array('name' => $k, 'error' => 1);
						}
						return array('elements' => $e, 'msgBoxes' => array(array('id' => 'general', 'elements' => array(array('html' => '<div class="errorBox">Eine oder mehrere Eingaben sind fehlerhaft!</div>')))));
					}

				// do upload

				case 'ifuSubmit':
					$this->getFoldersAndFiles();

					$upload = FilesystemFile::uploadFile('File', $this->currentFolder->getFilesystemFolder());

					if($upload === FALSE) {
						return array('msgBoxes' => array(array('id' => 'general', 'elements' => array(array('node' => 'div', 'properties' => array('className' => 'errorBox'), 'childnodes' => array(array('text' => 'Beim Upload der Datei ist ein Fehler aufgetreten!')))))));
					}

					else if($upload === NULL) {
						return array('msgBoxes' => array(array('id' => 'general', 'elements' => array(array('node' => 'div', 'properties' => array('className' => 'errorBox'), 'childnodes' => array(array('text' => 'Es wurde keine Datei zum Upload angegeben!')))))));
					}

					$form = new HtmlForm();

					$form->addElement(FormElementFactory::create('input', 'Title', '', array(), array(), FALSE, array('trim')));
					$form->addElement(FormElementFactory::create('input', 'Subtitle', '', array(), array(), FALSE, array('trim')));
					$form->addElement(FormElementFactory::create('input', 'customSort', '', array(), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT)));
					$form->addElement(FormElementFactory::create('input', 'referencedID', '', array(), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT_EXCL_NULL)));
					$form->addElement(FormElementFactory::create('input', 'referenced_Table', '', array(), array(), FALSE, array('trim')));
					$form->addElement(FormElementFactory::create('textarea', 'Description', ''));
					$form->addElement(FormElementFactory::create('checkbox', 'unpack_archives', 1));

					if($this->processFileUpload($form, $upload)) {
						return array('success' => TRUE);
					}

					return array('msgBoxes' => array(array('id' => 'general', 'elements' => array(array('node' => 'div', 'properties' => array('className' => 'errorBox'), 'childnodes' => array(array('text' => 'Beim Upload der Datei ist ein Fehler aufgetreten!')))))));

				case 'getFolderTree':
					return array('branches' => array($this->getFolderTree(isset($this->validatedRequests['id']) ? MetaFile::getInstance(NULL, $this->validatedRequests['id'])->getMetafolder() : NULL)));
		}
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
										'childnodes' => array(
											array(
												'text' => array_pop(explode(DIRECTORY_SEPARATOR, trim($f->getRelativePath(), DIRECTORY_SEPARATOR)))
											)
										)
									),
				'terminates'	=>	!count($branches),
				'branches'		=>	$branches,
				'current'		=>	$f === $currentFolder,
				'path'			=>	$f->getRelativePath()
			);
		};

		$trees = array();

		foreach(metaFolder::getRootFolders() as $f) {
			$trees[] = $parseFolder($f, $currentFolder);
		}

		return $trees[0];
	}

	private function getFoldersAndFiles() {
		try {
			MetaFolder::instantiateAllExistingMetaFolders();
		}
		catch(Exception $e) {}

		if(($id = $this->request->query->get('folder'))) {
			$this->currentFolder = MetaFolder::getInstance(NULL, $id);
		}

		else if(($id = $this->request->query->get('file'))) {
			$this->currentFolder = MetaFile::getInstance(NULL, $id)->getMetaFolder();
		}

		else {
			try {
				$this->currentFolder = MetaFolder::getInstance(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR).FILES_PATH);
			}
			catch(MetaFolderException $e) {
				$this->currentFolder = MetaFolder::create(FilesystemFolder::getInstance(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR).FILES_PATH));
			}
		}

		$this->cleanUpFolder($this->currentFolder);

		$folder = $this->currentFolder;
		$pathSegs = array($folder);

		while(($folder = $folder->getParentMetafolder())) {
			array_unshift($pathSegs, $folder);
		}

		$this->directoryBar = $pathSegs;
	}

	/**
	 * add metafolder entries for filesystem subfolders
	 * add metafile entries for filesystem files
	 * remove metafile entries for missing file system files
	 */
	private function cleanUpFolder(MetaFolder $metaFolder) {

		// delete orphaned metafolders

		$nestingInfo = $metaFolder->getNestingInformation();

		$mFolders = $this->db->doPreparedQuery('
			SELECT foldersID, Path, l, r FROM folders f WHERE l > ? AND r < ? AND f.level = ?
			', array(
				$nestingInfo['l'],
				$nestingInfo['r'],
				$nestingInfo['level'] + 1
		));

		$this->db->autocommit(FALSE);

		foreach($mFolders as $d) {
			if(!is_dir(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $d['Path'])) {

				$l = $d['l'];
				$r = $d['r'];
				$rl = $r - $l + 1;

				// delete "potential" subdirectories

				$this->db->execute("DELETE FROM folders WHERE l >= $l AND r <= $r");

				// update nesting

				$this->db->execute("UPDATE folders SET r = r - $rl WHERE r > $l");
			}
		}

		$this->db->autocommit(TRUE);

		// create metafolder entries for filesystemfolders

		foreach($metaFolder->getFilesystemFolder()->getFolders() as $d) {
			$d->createMetaFolder();
		}

		$mFiles = $this->db->doPreparedQuery('SELECT filesID, IFNULL(Obscured_Filename, File) AS Filename FROM files f WHERE f.foldersID = ?', array((int) $metaFolder->getId()));
		$existing = array();

		// delete orphaned metafile entries

		foreach($mFiles as $f) {
			if(! file_exists($metaFolder->getFullPath().$f['Filename'])) {
				$this->db->deleteRecord('files', $f['filesID']);
			}
			else {
				$existing[] = $f['Filename'];
			}
		}

		// add new filesystem files

		$fsFiles = Util::getDir($metaFolder->getFullPath());
		$missing = array_diff($fsFiles, $existing);

		foreach($missing as $m) {
			$add = FilesystemFile::getInstance($metaFolder->getFullPath().$m);
			$add->createMetaFile();
		}
	}

	/**
	 * turns uploaded file into metafile, avoiding filename collisions
	 *
	 * @param HtmlForm $form
	 * @param FilesystemFile $upload
	 * @throws FilesystemFileException::
	 * @return boolean success
	 */
	private function processFileUpload(HtmlForm $form, FilesystemFile $upload) {

		$values = $form->getValidFormValues();

		// check for archive

		if(preg_match('~^application/.*?(gz|zip|compressed)~', $upload->getMimeType()) && ! empty($values['unpack_archives'])) {
			try {
				$uploads = $this->handleArchive($upload);
				$upload->delete();
			}
			catch(Exception $e) {
				return FALSE;
			}
		}

		else {
			$uploads = array($upload);
		}

		foreach($uploads as $upload) {
			try {
				$mfFile = $upload->createMetaFile();
				$mfFile->setMetaData($values);

				// obscure file if folder has the Obscure_Files attribute set

				if($this->currentFolder->obscuresFiles()) {
					$this->obscureUpload($mfFile);
				}
			}

			// error with metafile creation(e.g. duplicate names with obscured files)

			catch(FilesystemFileException $e) {
				if($e->getCode() == FilesystemFileException::METAFILE_ALREADY_EXISTS) {
					preg_match('~^(.*?)(\((\d+)\))?(.[a-z0-9]*)?$~i', $upload->getFilename(), $matches);
					$matches[2] = $matches[2] == '' ? 2 : $matches[2] + 1;

					// check for both alternative filesystem filename and metafile filename

					while(file_exists("{$matches[1]}({$matches[2]}){$matches[4]}") || ! MetaFile::isFilenameAvailable("{$matches[1]}({$matches[2]}){$matches[4]}", $this->data['folder'])) {
						++ $matches[2];
					}

					$upload->rename("{$matches[1]}({$matches[2]}){$matches[4]}");

					$mfFile = $upload->createMetaFile();
					$mfFile->setMetaData($values);

					// obscure file if folder has the Obscure_Files attribute set

					if($this->currentFolder->obscuresFiles()) {
						$mfFile->obscureTo(uniqid());
						$this->obscureUpload($mfFile);
					}
				}

				else {
					throw $e;
				}
			}

			// other upload problem

			catch(Exception $e) {
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * extracts an archive and flattens it
	 * all extracted files are turned into Metafile objects
	 *
	 * @param FilesystemFile $f
	 * @throws Exception
	 * @returns array unzipped_filesystemfiles
	 */
	private function handleArchive(FilesystemFile $f) {

		$zip = new ZipArchive();
		$status = $zip->open($f->getPath());

		if($status !== TRUE) {
			throw new Exception("Archive file reports error: $status");
		}

		$path = $f->getFolder()->getPath();
		$metaFolder = MetaFolder::getInstance($path);
		$files = array();

		for($i = 0; $i < $zip->numFiles; ++ $i) {
			$name = $zip->getNameIndex($i);

			if(substr($name, - 1) == '/') {
				continue;
			}

			$dest = Util::checkFileName(basename($name), $path);

			copy("zip://{$f->getPath()}#$name", $path.$dest);
			$files[] = FilesystemFile::getInstance($path.$dest);
		}

		return $files;
	}

	private function obscureUpload(MetaFile $mf) {
		$mf->obscureTo(Uuid::generate());
	}
}
?>
