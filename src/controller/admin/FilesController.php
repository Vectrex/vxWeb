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
use vxPHP\Template\Filter\ImageCache;
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

		if(!$this->request->query->get('force')) {
			return new Response(SimpleTemplate::create('admin/files_js.php')->display());
		}

		// get folders and files
		$this->getFoldersAndFiles();

		// set target for redirect after successful operation
		$this->redirectQuery = array(
			'folder'	=> $this->currentFolder->getId(),
			'force'		=> 'htmlonly'
		);

		// delete file
		if(end($this->pathSegments) === 'del' && ($id = $this->request->query->getInt('file'))) {
			MetaFile::getInstance(NULL, $id)->delete();
			$this->redirect($this->route->getRouteId(), NULL, $this->redirectQuery);
		}

		// edit file
		if(end($this->pathSegments) === 'edit' && ($id = $this->request->query->getInt('file'))) {

			// cacheinfo not used yet

			$file = MetaFile::getInstance(NULL, $id);
			$form = $this->getEditForm($file);
			$form->bindRequestParameters();

			if($form->wasSubmittedByName('submit_cancel')) {
				$this->redirect($this->route->getRouteId(), NULL, $this->redirectQuery);
			}
			if($form->wasSubmittedByName('submit_edit')) {

				if(! $form->validate()->getFormErrors()) {
					try {
						$file->setMetaData($form->getValidFormValues());
						$this->redirect($this->route->getRouteId(), NULL, $this->redirectQuery);
					}
					catch(Exception $e) {
						$form->setError('system');
					}
				}
				else {
					$form->setError('general');
				}
			}
		}

		// add file

		if(end($this->pathSegments) === 'add') {

			$form = $this->getAddForm();
			$form->bindRequestParameters();

			if($form->wasSubmittedByName('submit_cancel')) {
				$this->redirect($this->route->getRouteId(), NULL, $this->redirectQuery);
			}

			if($form->wasSubmittedByName('submit_add')) {

				if(! $form->validate()->getFormErrors()) {

					$upload = FilesystemFile::uploadFile('File', $this->currentFolder->getFilesystemFolder());

					if($upload === FALSE) {
						$form->setError('system');
					}
					else if($upload === NULL) {
						$form->setError('file');
						$form->setError('general');
					}

					if(!$form->getFormErrors()) {

						$values = $form->getValidFormValues();

						if(FALSE !== vxWeb\FileUtil::processFileUpload($this->currentFolder, $upload, $values, isset($values['unpack_archives']))) {
							$this->redirect($this->route->getRouteId(), NULL, $this->redirectQuery);
						}
						else {
							$form->setError('system');
						}

					}

					else {
						$form->setError('system');
					}
				}

				else {
					$form->setError('general');
				}
			}
		}

		$tpl = SimpleTemplate::create('admin/files.php')
			->assign('directoryBar', $this->directoryBar)
			->assign('filesTable', $this->getFileTableHtml($this->currentFolder, $this->request->query->get('file')))
			->assign('folderID', $this->currentFolder->getId())
			->assign('folderName', $this->currentFolder->getName());

		if(isset($form)) {
			$tpl->assign('form', $form);
		}

		return new Response($tpl->display());
	}

	/**
	 * return markup of files table
	 * @param Metafolder $folder folder which content is returned
	 * @param $fileId id of edited file
	 */
	private function getFileTableHtml(MetaFolder $folder, $fileId = NULL) {

		$tpl = new SimpleTemplate('admin/files_table.php');
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

		$form->addElement(FormElementFactory::create('input', 'Title', NULL, array('maxlength' => 64, 'class' => 'xl'), array(), FALSE, array('trim')));
		$form->addElement(FormElementFactory::create('input', 'Subtitle', NULL, array('maxlength' => 64, 'class' => 'xl'), array(), FALSE, array('trim')));
		$form->addElement(FormElementFactory::create('input', 'customSort', NULL, array('maxlength' => 4, 'class' => 's'), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT)));
		$form->addElement(FormElementFactory::create('textarea', 'Description', NULL, array('rows' => 5, 'cols' => 40, 'class' => 'xl')));
		$form->addElement(FormElementFactory::create('input', 'referencedID', $file->getReferencedId(), array('maxlength' => 5, 'class' => 's'), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT_EXCL_NULL)));
		$form->addElement(FormElementFactory::create('input', 'referenced_Table', $file->getReferencedTable(), array('maxlength' => 32, 'class' => 'm'), array(), FALSE, array('trim')));

		$form->setInitFormValues($data);

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

	private function getFoldersAndFiles() {
		try {
			MetaFolder::instantiateAllExistingMetaFolders();
		}
		catch(Exception $e) {}

		if(($id = $this->request->query->getInt('folder')) || ($id = $this->request->request->getInt('folder'))) {
			$this->currentFolder = MetaFolder::getInstance(NULL, $id);
		}

		else if(($id = $this->request->query->getInt('file')) || ($id = $this->request->request->getInt('file'))) {
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

		vxWeb\FileUtil::cleanupMetaFolder($this->currentFolder);

		$folder = $this->currentFolder;
		$pathSegs = array($folder);

		while(($folder = $folder->getParentMetafolder())) {
			array_unshift($pathSegs, $folder);
		}

		$this->directoryBar = $pathSegs;
	}
}
