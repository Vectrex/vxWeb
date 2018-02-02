<?php

namespace App\Controller\Admin;

/* @TODO sanitize metadata */

use vxPHP\Util\Rex;

use vxPHP\File\Exception\FilesystemFileException;
use vxPHP\File\FilesystemFile;
use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Image\ImageModifierFactory;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Template\Filter\ImageCache;
use vxPHP\Template\Filter\AssetsPath;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;
use vxPHP\Application\Application;
use vxWeb\Model\Article\Article;
use vxPHP\File\MimeTypeGetter;
use vxPHP\Constraint\Validator\RegularExpression;

use vxWeb\Model\MetaFile\MetaFile;
use vxWeb\Model\MetaFile\MetaFolder;
use vxWeb\Model\MetaFile\Exception\MetaFileException;
use vxWeb\Model\MetaFile\Exception\MetaFolderException;
use vxWeb\Util\File;

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
				->assign('upload_max_filesize', $uploadMaxFilesize)
				->assign('max_execution_time_ms', $maxExecutionTime * 900) // 10pct "safety margin"
				->display());
	}

	/**
	 * simple helper function to convert ini values like 10M or 256K to integer
	 *
	 * @param string $val
     * @return string
	 */
	private function toBytes($val) {

		$suffix = strtolower(substr(trim($val),-1));
		
		$val = (int) $val;
		
		switch($suffix) {
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

			$fsFolder = $folder->getFilesystemFolder();
		}

		catch(MetaFolderException $e) {
			return new JsonResponse(['error' => $e->getMessage()]);
		}

		// get articles reference

		if($articlesId = $this->request->query->get('articlesId')) {
			$article = Article::getInstance($articlesId);
		}

		// get filename

		$filename = FilesystemFile::sanitizeFilename(urldecode($this->request->headers->get('x-file-name')), $fsFolder);
		$contents = file_get_contents('php://input');

		try {
			$mimeType = MimeTypeGetter::getForBuffer($contents);
		}
		catch (\RuntimeException $e) {
			$mimeType = '';
		}

		try {
			if($mimeType === 'application/zip' && $this->request->query->getInt('unpack')) {

				// unpack ZIP files

				$files = [];

				$tmpFile	= tmpfile();
				$tmpName	= stream_get_meta_data($tmpFile)['uri'];
				fwrite($tmpFile, $contents);
				fseek($tmpFile, 0);

				$zip = new \ZipArchive();

				if($zip->open($tmpName)) {

					for($i = 0; $i < $zip->numFiles; ++$i) {

						$name = $zip->getNameIndex($i);

						if(substr($name, -1) == '/') {
							continue;
						}

						$dirname = dirname($name);

						if($dirname && $dirname !== '.' ) {
							$dir = $fsFolder->createFolder($dirname);
						}
						else {
							$dir = $fsFolder;
						}

						$dest = FilesystemFile::sanitizeFilename(basename($name), $dir);

						copy('zip://' . $tmpName . '#' . $name, $dir->getPath() . $dest);

						$files[] = FilesystemFile::getInstance($dir->getPath() . $dest);

					}

					$zip->close();

					// link to article, when in "article" mode

					if(isset($article)) {

						foreach($files as $file) {
							$article->linkMetaFile(MetaFile::createMetaFile($file));
						}

						$article->save();

					}
				}
			}

			else {

				file_put_contents($fsFolder->getPath() . $filename, $contents);

				// link to article, when in "article" mode

				if(isset($article)) {
					$article->linkMetaFile(MetaFile::createMetaFile(FilesystemFile::getInstance($fsFolder->getPath() . $filename)));
					$article->save();
				}
			}
		}

		catch(\Exception $e) {
			return new JsonResponse(
				[
					'response' => [
						'error' => 1,
						'message' => sprintf("Upload von '%s' fehlgeschlagen: %s.", $filename, $e->getMessage())
					]
				]
			);
		}

		// @todo better way to handle columns

		$fileColumns = ['name', 'size', 'mime', 'mTime'];

		if(isset($article)) {
			$fileColumns[] = 'linked';
		}

		return new JsonResponse([
			'echo' => ['folder' => $id],
			'response' => $this->getFiles($folder, $fileColumns)
		]);

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
					$response = ['error' => FALSE];
				}
				catch(\Exception $e) {
					$response = ['error' => $e->getMessage()];
				}
				break;

			// unlink file from an article

			case 'unlinkFromArticle':
				try {
					$article = Article::getInstance($this->request->request->getInt('articlesId'));
					$article->unlinkMetaFile(MetaFile::getInstance(NULL, $this->request->request->getInt('file')));
					$article->save();
					$response = ['error' => FALSE];
				}
				catch(\Exception $e) {
					$response = ['error' => $e->getMessage()];
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
				$response = $this->getAddForm()->render();
				break;

			// get complete folder tree

			case 'getFolderTree':
			    $trees = $this->getFolderTree(($id = $this->request->request->getInt('file')) ? MetaFile::getInstance(null, $id)->getMetafolder() : null);
				$response = $this->renderFolderTree($trees[0]);
				break;

			// return form for editing file

			case 'requestEditForm':
				if(($id = $this->request->request->getInt('file'))) {
					$markup = $this->getEditForm(MetaFile::getInstance(NULL, $id))->render();

					ImageCache::create()->apply($markup);
					AssetsPath::create()->apply($markup);

					$response = $markup;
				}
				else {
					$response = ['error' => TRUE];
				}
				break;

			// check and update edit data

			case 'checkEditForm':

				$file = MetaFile::getInstance(NULL, $this->request->request->getInt('file'));
				$form = $this->getEditForm($file);

				if(!($errors = $form
					->bindRequestParameters($this->request->request)
					->disableCsrfToken()
					->validate()
					->getFormErrors()
				)) {
					$file->setMetaData($form->getValidFormValues()->all());
					$response = $this->getFiles($file->getMetafolder());
				}

				else {
					$e = [];
					foreach(array_keys($errors) as $k) {
						$e[] = ['name' => $k, 'error' => 1];
					}

					$response = [
						'elements' => $e,
						'msgBoxes' => [
							[
								'id' => 'general',
								'elements' => [['html' => '<div class="errorBox">Eine oder mehrere Eingaben sind fehlerhaft!</div>']]
							]
						]
					];
				}
				break;

			// validate new file data before upload

			case 'checkUpload':

				$form = $this->getAddForm();

				if(!($errors = $this->getAddForm()
					->bindRequestParameters($this->request->request)
					->disableCsrfToken()
					->validate()
					->getFormErrors()
				)) {
					$response = ['command' => 'submit'];
				}
				else {
					$e = [];
					foreach(array_keys($errors) as $k) {
						$e[] = ['name' => $k, 'error' => 1];
					}

					$response = [
						'elements' => $e,
						'msgBoxes' => [
							[
								'id' => 'general',
								'elements' => [['html' => '<div class="errorBox">Eine oder mehrere Eingaben sind fehlerhaft!</div>']]
							]
						]
					];
				}
				break;

			// do upload

			case 'ifuSubmit':

				$upload = $this->request->files->get('File');

				// was a file uploaded at all?

				if($upload === NULL) {
					$response = [
						'msgBoxes' => [
							[
								'id' => 'general',
								'elements' => [
									[
										'node' => 'div',
										'properties' => ['className' => 'errorBox'],
										'childnodes' => [['text' => 'Es wurde keine Datei zum Upload angegeben!']]
									]
								]
							]
						]
					];
					break;
				}

				// try to move uploaded file to its final destination

				try {
					$upload->move($folder->getFilesystemFolder());
				}

				catch(FilesystemFileException $e) {
					$response = [
						'msgBoxes' => [
							[
								'id' => 'general',
								'elements' => [
									[
										'node' => 'div',
										'properties' => ['className' => 'errorBox'],
										'childnodes' => [['text' => 'Beim Upload der Datei ist ein Fehler aufgetreten!']]
									]
								]
							]
						]
					];
					break;
				}

				/*
				 * @todo
				 * Kludge: HtmlForm calls Request::createFromGlobals() which in turn parses $_FILES and throws an exception
				 * since the uploaded file was already moved
				 */
				unset($_FILES['File']);

				$form = HtmlForm::create()
					->addElement(FormElementFactory::create('input', 'title', '', [], [], FALSE, ['trim']))
					->addElement(FormElementFactory::create('input', 'subtitle', '', [], [], FALSE, ['trim']))
					->addElement(FormElementFactory::create('input', 'customsort', '', [], [], FALSE, ['trim'], [new RegularExpression(Rex::EMPTY_OR_INT)]))
					->addElement(FormElementFactory::create('textarea', 'description', ''))
					->addElement(FormElementFactory::create('checkbox', 'unpack_archives', 1));

				$values = $form->bindRequestParameters($this->request->request)->getValidFormValues();

				// turn uploaded file into metafile, extract archive if neccessary

				$uploadedFiles = File::processFileUpload($folder, $upload, $values->all(), (bool) $values->get('unpack_archives'));

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

					$response = ['success' => TRUE];
				}

				else {
					$response = [
						'msgBoxes' => [
							[
								'id' => 'general',
								'elements' => [
									[
										'node' => 'div',
										'properties' => ['className' => 'errorBox'],
										'childnodes' => [['text' => 'Beim Upload der Datei ist ein Fehler aufgetreten!']]
									]
								]
							]
						]
					];
				}

				// avoid browser-side decoration of response with <pre> tags, since this response will end up in an IFrame

				return new Response(json_encode($response));

				break;

			default:
				$response = null;
		}

		return $this->addEchoToJsonResponse(new JsonResponse($response));
	}

	private function renameFile() {

		try {
			$file = MetaFile::getInstance(NULL, $this->request->request->getInt('file'));
			$user = Application::getInstance()->getCurrentUser();

			if($user->hasRole('superadmin') || $user->getAttribute('id') == $file->getData('createdby')) {

				$file->rename(trim($this->request->request->get('filename')));

				$metaData = $file->getData();

				return [
					'filename' => $file->getMetaFilename(),
					'elements' => ['html' => sprintf("<span title='%s'>%s</span>", $metaData['title'], $file->getMetaFilename())],
					'error' => FALSE
				];
			}
		}
		catch (MetaFileException $e) {
			return ['error' => $e->getMessage()];
		}
	}

	private function delFile() {

		try {
			$file = MetaFile::getInstance(NULL, $this->request->request->getInt('file'));
			$user = Application::getInstance()->getCurrentUser();

			if($user->hasRole('superadmin') || $user->getAttribute('id') == $file->getData('createdby')) {

				$folder = $file->getMetaFolder();
				$file->delete();

				return $this->getFiles($folder);
			}
		}

		catch (MetaFileException $e) {
			return ['error' => $e->getMessage()];
		}
	}

	private function moveFile() {

		try {

			$file = MetaFile::getInstance(NULL, $this->request->request->getInt('file'));
			$user = Application::getInstance()->getCurrentUser();
			
			if($user->hasRole('superadmin') || $user->getAttribute('id') == $file->getData('createdby')) {

				$folder = $file->getMetafolder();
				$file->move(MetaFolder::getInstance(NULL, $this->request->request->getInt('destination')));

				return $this->getFiles($folder);
			}
		}
		catch (\Exception $e) {
			return ['error' => $e->getMessage()];
		}
	}

	private function addFolder(MetaFolder $folder) {

        $folderName = preg_replace('~[^a-z0-9_-]~i', '_', $this->request->request->get('folderName'));

        foreach($folder->getMetaFolders() as $subFolder) {

            if($subFolder->getName() === $folderName) {
                return ['error' => sprintf("Verzeichnis '%s' existiert bereits.", $folderName)];
            }

        }

		try {
			$folder->createFolder($folderName);
			return $this->getFiles($folder);
		}
		catch (\Exception $e) {
            return ['error' => $e->getMessage()];
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
			return ['error' => $e->getMessage()];
		}
	}

	private function getFiles(MetaFolder $mf, array $fileColumns = NULL) {

		File::cleanupMetaFolder($mf);

		if(!$fileColumns) {
			$fileColumns = $this->request->request->get('fileColumns', ['name', 'size', 'mime', 'mTime']);
		}

		$folders	= $this->getFolderList($mf);
		$files		= $this->getFileList($mf, $fileColumns);

		$pathSegments = [['name' => $mf->getName(), 'id' => $mf->getId()]];

		while(($mf = $mf->getParentMetafolder())) {
			array_unshift($pathSegments, ['name' => $mf->getName(), 'id' => $mf->getId()]);
		}

		switch($this->route->getRouteId()) {

			case 'filepickerXhr':
				$fileFunctions = ['rename', 'edit', 'move', 'del', 'forward'];
				break;

			default:
				$fileFunctions = ['rename', 'edit', 'move', 'del'];
		}

		return [
			'pathSegments'	=> $pathSegments,
			'folders'		=> $folders,
			'files'			=> $files,
			'fileFunctions'	=> $fileFunctions
		];
	}

	private function getAddForm() {

		return HtmlForm::create('admin_file.htm')
			->initVar('add', 1)
			->setEncType('multipart/form-data')
			->setAttribute('class', 'editFileForm')
			->addElement(FormElementFactory::create('input', 'title', '', [], [], FALSE, ['trim']))
			->addElement(FormElementFactory::create('input', 'subtitle', '', [], [], FALSE, ['trim']))
			->addElement(FormElementFactory::create('input', 'customsort', '', [], [], FALSE, ['trim'], [new RegularExpression(Rex::EMPTY_OR_INT)]))
			->addElement(FormElementFactory::create('input', 'File', '', ['type' => 'file']))
			->addElement(FormElementFactory::create('checkbox', 'unpack_archives', 1))
			->addElement(FormElementFactory::create('textarea', 'description'))
			->addElement(FormElementFactory::create('button', 'submit_add')->setInnerHTML('Speichern'));
	}

	private function getEditForm(MetaFile $file) {

		$data		= array_change_key_case($file->getData(), CASE_LOWER);
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
					$data['file'],
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
					$data['file'],
					$file->getMimetype(),
					$cacheText,
					$file->getRelativePath(),
					$assetsPath,
					$file->getRelativePath(),
					$file->getRelativePath()
			);
		}

		return HtmlForm::create('admin_file.htm')
			->setAttribute('class', 'editFileForm')
			->addElement(FormElementFactory::create('input', 'title', NULL, [], [], FALSE, ['trim']))
			->addElement(FormElementFactory::create('input', 'subtitle', NULL, [], [], FALSE, ['trim']))
			->addElement(FormElementFactory::create('input', 'customsort', NULL, [], [], FALSE, ['trim'], [new RegularExpression(Rex::EMPTY_OR_INT)]))
			->addElement(FormElementFactory::create('textarea', 'description'))
			->addElement(FormElementFactory::create('button', 'submit_edit')->setInnerHTML('Speichern'))
			->addMiscHtml('Fileinfo', $infoHtml)
			->setInitFormValues($data);
	}

	private function getFolderList(MetaFolder $folder) {

		$folders	= [];

		foreach($folder->getMetaFolders() as $f) {
			$folders[] = [
				'id'	=> $f->getId(),
				'name'	=> $f->getName()
			];
		}

		return $folders;
	}

	private function getFileList(MetaFolder $folder, array $columns) {

		$files		= [];
		$app		= Application::getInstance();
		$assetsPath	= !$app->hasNiceUris() ? ltrim($app->getRelativeAssetsPath(), '/') : '';

		if($articlesId = $this->request->query->get('articlesId', $this->request->request->get('articlesId'))) {
			$linkedFiles = Article::getInstance($articlesId)->getLinkedMetaFiles();
		}

		foreach(MetaFile::getMetaFilesInFolder($folder) as $f) {

			$isImage	= $f->isWebImage();
			$metaData	= $f->getData();
			$file		= ['columns' => [], 'id' => $f->getId(), 'filename' => $f->getMetaFilename()];

			foreach($columns as $c) {

				switch($c) {
					case 'name':
						$file['columns'][] = ['html' => sprintf('<span title="%s">%s</span>', $metaData['title'], $f->getMetaFilename())];
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
							$actions	= ['crop 1', 'resize 0 40'];
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
										call_user_func_array([$imgEdit, $method], $params);
									}
								}
								$imgEdit->export($dest);
							}

							$file['columns'][] = ['html' => '<img class="thumb" src="' . htmlspecialchars(str_replace(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR), '', $dest)) . '" alt="" />'];
						}

						else {
							$file['columns'][] = $f->getMimetype();
						}
						break;

					case 'linked':
						if(isset($linkedFiles) && in_array($f, $linkedFiles, TRUE)) {
							$file['columns'][] = ['html' => '<label class="form-switch"><input type="checkbox" class="link" checked="checked"><i class="form-icon"></i></label>'];
							$file['linked'] = TRUE;
						}
						else {
							$file['columns'][] = ['html' => '<label class="form-switch"><input type="checkbox" class="link"><i class="form-icon"></i></label>'];
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
				$file['forward'] = ['filename' => '/' . $assetsPath . $f->getRelativePath(), 'ckEditorFuncNum' => (int) $this->request->query->get('CKEditorFuncNum')];
			}

			$files[] = $file;
		}

		return $files;
	}

	private function getFolderTree(MetaFolder $currentFolder = null) {

		$parseFolder = function(MetaFolder $f, $currentFolder) use (&$parseFolder) {

			$subTrees = $f->getMetaFolders();

			$branches = [];

			if(count($subTrees)) {
				foreach($subTrees as $s) {
					$branches[] = $parseFolder($s, $currentFolder);
				}
			}

			$pathSegs = explode(DIRECTORY_SEPARATOR, trim($f->getRelativePath(), DIRECTORY_SEPARATOR));

			return [
				'key' => $f->getId(),
				'label' => end($pathSegs),
				'branches' => $branches,
				'current' => $f === $currentFolder,
				'path' => $f->getRelativePath()
			];
		};

		$trees = [];

		foreach(MetaFolder::getRootFolders() as $f) {
			$trees[] = $parseFolder($f, $currentFolder);
		}

		return $trees;

	}

	private function renderFolderTree($tree) {

        $markup = '';

        $renderTree = function($treeData) use (&$markup, &$renderTree) {

            $className = $treeData['current'] ? 'current' : '';
            $className .= empty($treeData['branches']) ? ' terminates' : '';

            $markup .= sprintf(
                '<li class="%s"><input type="checkbox" id="treeBranch_%d"><label for="treeBranch_%d"></label><span id="folder_%d">%s</span>',
                trim($className),
                $treeData['key'],
                $treeData['key'],
                $treeData['key'],
                $treeData['label']
            );

            if(!empty($treeData['branches'])) {
                $markup .= '<ul>';

                foreach($treeData['branches'] as $branch) {
                    $markup .= $renderTree($branch);
                }

                $markup .= '</ul>';
            }

            $markup .= '</li>';

        };

        $renderTree($tree);

        return '<ul class="vx-tree">' . $markup . '</ul>';

    }
}
