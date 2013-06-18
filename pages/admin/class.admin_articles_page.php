<?php

use vxPHP\Util\Rex;
use vxPHP\Util\Uuid;

use vxPHP\User\Admin;

use vxPHP\Template\SimpleTemplate;

use vxPHP\Image\ImageModifier;

use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\Validator\DateTime;

use vxPHP\File\MetaFile;
use vxPHP\File\MetaFolder;
use vxPHP\File\FilesystemFile;
use vxPHP\File\FilesystemFolder;
use vxPHP\File\Exception\MetaFileException;
use vxPHP\File\Exception\FilesystemFileException;
use vxPHP\File\Exception\MetaFolderException;

use vxPHP\Orm\Custom\Article;
use vxPHP\Orm\Custom\ArticleCategory;
use vxPHP\Orm\Custom\Exception\ArticleException;
use vxPHP\Orm\Custom\Exception\ArticleCategoryException;

class admin_articles_page extends page {
	protected $pageRequests = array(
		'id'		=> Rex::INT_EXCL_NULL,
		'action'	=> array('new', 'del')
	);
	protected	$categories,
				$categoriesAlias;

	private		$articleForm,
				$filesForm;

	public function __construct() {
		parent::__construct();

		$admin = Admin::getInstance();

		// check permission of non superadmin

		if(isset($this->validatedRequests['id'])) {
			try {
				$article = Article::getInstance($this->validatedRequests['id']);
			}
			catch(ArticleException $e) {
				$this->redirect('?page=articles');
			}
			if(!$admin->hasSuperAdminPrivileges() && $admin->getAdminId() != $article->getCreatedBy()->getAdminId()) {
				$this->redirect('?page=articles');
			}
		}

		if(isset($this->validatedRequests['id']) && isset($this->validatedRequests['action']) && $this->validatedRequests['action'] == 'del') {
			$files = $article->getReferencingFiles();
			try {
				$article->delete();
			}
			catch(ArticleException $e) {
				$this->redirect('admin.php?page=articles');
			}
			foreach($files as $f) {
				$f->delete();
			}
			$this->redirect('admin.php?page=articles');
		}

		if(isset($this->validatedRequests['action']) && $this->validatedRequests['action'] === 'new' || isset($this->validatedRequests['id'])) {

			// fill category related properties - replacing default method allows user privilege considerations

			$categories = $this->getArticleCategories();

			$this->articleForm = new HtmlForm('admin_edit_article.htm');
			$this->articleForm->setAttribute('class', 'editArticleForm');

			$submitButton = FormElementFactory::create('button', 'submit_article', '', array('type' => 'submit', 'class' => 'l'));

			if(isset($article)) {

				$data = $article->getData();

				$this->articleForm->setInitFormValues(array(
					'articlecategoriesID'	=> $article->getCategory()->getId(),
					'Headline'				=> $article->getHeadline(),
					'Teaser'				=> isset($data['Teaser']) ? $data['Teaser'] : '',
					'Content'				=> isset($data['Content']) ? htmlspecialchars($data['Content'], ENT_NOQUOTES, 'UTF-8') : '',
					'Article_Date'			=> is_null($article->getDate())			? '' : $article->getDate()->format('d.m.Y'),
					'Display_from'			=> is_null($article->getDisplayFrom())	? '' : $article->getDisplayFrom()->format('d.m.Y'),
					'Display_until'			=> is_null($article->getDisplayUntil())	? '' : $article->getDisplayUntil()->format('d.m.Y')
				));

				$submitButton->setInnerHTML('Änderungen übernehmen');
				$this->initFilesForm($article);
			}

			else {

				$article = new Article();

				$submitButton->setInnerHTML('Newsmeldung anlegen');
				$this->articleForm->initVar('is_add', 1);
			}

			$this->articleForm->addElement($submitButton);
			$this->articleForm->addElement(FormElementFactory::create('select', 'articlecategoriesID', NULL, array('size' => 1, 'class' => 'xxl'), $categories, FALSE, array(), array(Rex::INT_EXCL_NULL)));
			$this->articleForm->addElement(FormElementFactory::create('input', 'Headline', NULL, array('maxlength' => 200, 'class' => 'xxl'), array(), FALSE, array('trim'), array(Rex::NOT_EMPTY_TEXT)));
			$this->articleForm->addElement(FormElementFactory::create('textarea', 'Teaser', NULL, array('rows' => 3, 'cols' => 40, 'class' => 'xxl'), array(), FALSE, array('trim', 'strip_tags')));
			$this->articleForm->addElement(FormElementFactory::create('textarea', 'Content', NULL, array('rows' => 10, 'cols' => 40, 'class' => 'xxl'), array(), FALSE, array('trim'), array(Rex::NOT_EMPTY_TEXT)));
			$this->articleForm->addElement(FormElementFactory::create('input', 'Article_Date', NULL, array('maxlength' => 10, 'class' => 'm'), array(), FALSE, array('trim')));
			$this->articleForm->addElement(FormElementFactory::create('input', 'Display_from', NULL, array('maxlength' => 10, 'class' => 'm'), array(), FALSE, array('trim')));
			$this->articleForm->addElement(FormElementFactory::create('input', 'Display_until', NULL, array('maxlength' => 10, 'class' => 'm'), array(), FALSE, array('trim')));

			if($this->articleForm->wasSubmittedByName('submit_article')) {
				$this->articleForm->validate();
				$v = $this->articleForm->getValidFormValues();

				if($v['Article_Date'] != '') {
					if(!DateTime::checkDate($v['Article_Date'])) {
						$this->articleForm->setError('Article_Date');
					}
					else {
						$article->setDate(new \DateTime($this->db->formatDate($v['Article_Date'])));
					}
				}

				if($v['Display_from'] != '') {
					if(!DateTime::checkDate($v['Display_from'])) {
						$this->articleForm->setError('Display_from');
					}
					else {
						$article->setDisplayFrom(new \DateTime($this->db->formatDate($v['Display_from'])));
					}
				}

				if($v['Display_until'] != '') {
					if(!DateTime::checkDate($v['Display_until'])) {
						$this->articleForm->setError('Display_until');
					}
					else {
						$article->setDisplayUntil(new \DateTime($this->db->formatDate($v['Display_until'])));
					}
				}

				if(!$this->articleForm->getFormErrors()) {
					try {

						// validate submitted category id - replacing default method allows user privilege considerations

						$article->setCategory($this->validateArticleCategory(ArticleCategory::getInstance($v['articlecategoriesID'])));
						$article->setHeadline($v['Headline']);
						$article->setData(array('Teaser' => $v['Teaser'], 'Content' => $v['Content']));

						$article->save();
						$this->redirect("admin.php?page=articles&id={$article->getId()}");
					}
					catch(ArticleException $e) {
						$this->articleForm->setError('system');
					}
					catch(ArticleCategoryException $e) {
						$this->articleForm->setError('system');
					}
				}
			}

			if(isset($this->filesForm) && $this->filesForm->wasSubmittedByName('submit_file')) {
				$vals	= $this->filesForm->getValidFormValues();

				if(isset($vals['delete_file'])) {
					foreach($vals['delete_file'] as $k => $v) {
						if($v == 1) {
							MetaFile::getInstance(NULL, (int) $k)->delete();
						}
					}
				}
				$this->uploadArticleFile($article, $vals);
				$this->redirect("admin.php?page=articles&id={$this->validatedRequests['id']}");
			}
			return;
		}

		$admin = Admin::getInstance();
		$restrictingWhere = $admin->hasSuperAdminPrivileges() ? '1 = 1' : "a.createdBy = {$admin->getAdminId()}";

		$this->data['articles'] = $this->db->doQuery("
			SELECT
				articlesID, Headline, Content, Article_Date, a.lastUpdated, a.firstCreated, c.Alias, c.Title
			FROM
				articles a
				INNER JOIN articlecategories c ON a.articlecategoriesID = c.articlecategoriesID
			WHERE
				$restrictingWhere
			ORDER BY
				lastUpdated DESC
		", TRUE, array('htmlspecialchars'));
	}

	public function content() {
		if(isset($this->articleForm)) {
			$tpl = new SimpleTemplate('admin_articles_edit.htm');
			$tpl->assign('backlink', $this->currentPage);
			$tpl->assign('article_form', $this->articleForm->render());
			$tpl->assign('files_form', isset($this->filesForm) ? $this->filesForm->render() : '');
		}
		else {
			$tpl = new SimpleTemplate('admin_articles_list.htm');
			$tpl->assign('articles', $this->data['articles']);
			$tpl->assign('page', $this->currentPage);
		}
		$html = $tpl->display();
		$this->html .= $html;
		return $html;
	}

	private function getArticleCategories() {
		$categories = array('-1' => 'Bitte Kategorie wählen...');

		foreach(ArticleCategory::getArticleCategories('sortByCustomSort') as $c) {
			$categories[$c->getId()] = $c->getTitle();
		}

		return $categories;
	}

	private function validateArticleCategory(ArticleCategory $cat) {
		return $cat;
	}

	private function getThumbPath(MetaFile $f) {

		// check and - if required - generate thumbnail

		$fi			= $f->getFileInfo();
		$actions	= array('crop 1', 'resize 0 40');
		$dest		=
			$f->getMetaFolder()->getFilesystemFolder()->createCache().
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

		return str_replace(rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR), '', $dest);
	}

	private function uploadArticleFile(Article $article, array $metaData) {

		$parentFolder = FilesystemFolder::getInstance(rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR).FILES_ARTICLES_PATH);

		// @todo avoid collision of folder names with existing file names

		if(!is_dir($parentFolder->getPath().$article->getCategory()->getAlias())) {
			$metaFolder = $parentFolder->createFolder($article->getCategory()->getAlias())->createMetafolder();
		}
		else {
			try {
				$metaFolder = MetaFolder::getInstance($parentFolder->getPath().$article->getCategory()->getAlias().DIRECTORY_SEPARATOR);
			}
			catch(MetaFolderException $e) {
				$metaFolder = $parentFolder->createMetaFolder();
			}
		}

		if(($f = FilesystemFile::uploadFile('upload_file', $metaFolder->getFileSystemFolder()))) {

			try {
				$mf = $f->createMetaFile();
			}

			catch(FilesystemFileException $e) {
				if($e->getCode() == FilesystemFileException::METAFILE_ALREADY_EXISTS) {
					preg_match('~^(.*?)(\((\d+)\))?(.[a-z0-9]*)?$~i', $f->getFilename(), $matches);
					$matches [2] = $matches [2] == '' ? 2 : $matches [2] + 1;

					// check for both alternative filesystem filename and metafile filename

					while(file_exists("{$matches[1]}({$matches[2]}){$matches[4]}") || ! MetaFile::isFilenameAvailable("{$matches[1]}({$matches[2]}){$matches[4]}", $this->data ['folder'])) {
						+ $matches[2];
					}

					$f->rename("{$matches[1]}({$matches[2]}){$matches[4]}");

					$mf = $f->createMetaFile();
				}
				else {
					throw $e;
				}
			}

			$mf->setMetaData(array(
					'Description'		=> htmlspecialchars($metaData['file_description']),
					'referenced_Table'	=> 'articles',
					'referencedID'		=> $article->getId()
			));

			if($metaFolder->obscuresFiles()) {
				$mf->obscureTo(Uuid::generate());
			}

			return $mf;
		}
	}

	private function initFilesForm(Article $article) {

		$cbValues		= array();
		$mimetypes		= array();
		$descriptions	= array();
		$filenames		= array();

		$this->filesForm = new HtmlForm('admin_edit_article_files.htm');

		$this->filesForm->setEncType('multipart/form-data');
		$this->filesForm->setAttribute('class', 'editArticleForm');

		if(!is_null($article->getId())) {
			$this->filesForm->addElement(FormElementFactory::create('input', 'id', $article->getId(), array('type' => 'hidden')));

			foreach($article->getReferencingFiles() as $f) {
				$data = $f->getData();

				$cbValues[$data['filesID']]	= 1;
				$descriptions[]				= $data['Description'];
				$filenames[]				= $data['File'];
				$mimetypes[]				= $f->isWebImage() ? "<img class='thumb' src='/{$f->getRelativePath()}#crop 1|resize 0 40' alt=''>" : $f->getMimetype();
			}
		}

		$this->filesForm->initVar	('files_count',		count($filenames));
		$this->filesForm->addMiscHtml('mimetypes',		$mimetypes);
		$this->filesForm->addMiscHtml('descriptions',	$descriptions);
		$this->filesForm->addMiscHtml('filenames',		$filenames);

		$this->filesForm->addElementArray(FormElementFactory::create('checkbox', 'delete_file', $cbValues));
		$this->filesForm->addElement(FormElementFactory::create('input', 'upload_file', '', array('type' => 'file')));
		$this->filesForm->addElement(FormElementFactory::create('textarea', 'file_description', '', array('rows' => 2, 'cols' => 40, 'class' => 'xl')));

		$fileSubmit = FormElementFactory::create('button', 'submit_file', '', array('type' => 'submit'));
		$fileSubmit->setInnerHTML('Datei(en) hinzufügen/löschen');

		$this->filesForm->addElement($fileSubmit);
	}

	protected function handleHttpRequest() {

		if(isset($this->validatedRequests['id'])) {
			try {
				$article = Article::getInstance($this->validatedRequests['id']);
			}
			catch(ArticleException $e) {
				return;
			}
		}

		else if(isset($this->validatedRequests['elements']) && isset($this->validatedRequests['elements']['id'])) {
			try {
				$article = Article::getInstance($this->validatedRequests['elements']['id']);
			}
			catch(ArticleException $e) {
				return;
			}
		}

		else {
			$article = new Article();
		}

		switch($this->validatedRequests['httpRequest']) {

			// check article data

			case 'checkForm':
				$_POST = $this->validatedRequests['elements'];

				$form = new HtmlForm('admin_edit_article.htm');

				$form->addElement(FormElementFactory::create('select', 'articlecategoriesID', NULL, array(), array(), FALSE, array(), array(Rex::INT_EXCL_NULL)));
				$form->addElement(FormElementFactory::create('input', 'Headline', NULL, array(), array(), FALSE, array('trim'), array(Rex::NOT_EMPTY_TEXT)));
				$form->addElement(FormElementFactory::create('textarea', 'Teaser', NULL, array(), array(), FALSE, array('trim', 'strip_tags')));
				$form->addElement(FormElementFactory::create('textarea', 'Content', NULL, array(), array(), FALSE, array('trim'), array(Rex::NOT_EMPTY_TEXT)));
				$form->addElement(FormElementFactory::create('input', 'Article_Date', NULL, array(), array(), FALSE, array('trim')));
				$form->addElement(FormElementFactory::create('input', 'Display_from', NULL, array(), array(), FALSE, array('trim')));
				$form->addElement(FormElementFactory::create('input', 'Display_until', NULL, array(), array(), FALSE, array('trim')));

				$form->validate();
				$v = $form->getValidFormValues();

				if($v['Article_Date'] != '') {
					if(!DateTime::checkDate($v['Article_Date'])) {
						$this->articleForm->setError('Article_Date');
					}
					else {
						$article->setDate(new \DateTime($this->db->formatDate($v['Article_Date'])));
					}
				}

				if($v['Display_from'] != '') {
					if(!DateTime::checkDate($v['Display_from'])) {
						$this->articleForm->setError('Display_from');
					}
					else {
						$article->setDisplayFrom(new \DateTime($this->db->formatDate($v['Display_from'])));
					}
				}

				if($v['Display_until'] != '') {
					if(!DateTime::checkDate($v['Display_until'])) {
						$this->articleForm->setError('Display_until');
					}
					else {
						$article->setDisplayUntil(new \DateTime($this->db->formatDate($v['Display_until'])));
					}
				}

				$errors	= $form->getFormErrors();

				if(!empty($errors)) {
					$texts	= $form->getErrorTexts();

					$response = array();
					foreach(array_keys($errors) as $err) {
						$response[] = array('name' => $err, 'error' => 1, 'errorText' => isset($texts[$err]) ? $texts[$err] : NULL);
					}
					return array('elements' => $response);
				}

				try {

					// validate submitted category id - replacing default method allows user privilege considerations

					$article->setCategory($this->validateArticleCategory(ArticleCategory::getInstance($v['articlecategoriesID'])));
					$article->setHeadline($v['Headline']);
					$article->setData(array('Teaser' => $v['Teaser'], 'Content' => $v['Content']));

					$id = $article->getId();

					if($article->wasChanged()) {
						$article->save();
						if(!$id) {
							$this->initFilesForm($article);
							return array('success' => TRUE, 'markup' => array('html' => $this->filesForm->render()), 'id' => $article->getId());
						}
						else {
							return array('success' => TRUE);
						}
					}

					else {
						return array('success' => TRUE, 'message' =>'Keine Änderung, nichts gespeichert!');
					}
				}
				catch(ArticleException $e) {
					return array('message' => 'Beim Anlegen/Aktualisieren des Artikels ist ein Fehler aufgetreten!');
				}
				catch(ArticleCategoryException $e) {
					return array('message' => 'Beim Anlegen/Aktualisieren des Artikels ist ein Fehler aufgetreten!');
				}

			case 'ifuSubmit':
				$success = TRUE;
				$errorMsg = '';

				if(isset($_POST['delete_file'])) {
					foreach(array_keys($_POST['delete_file']) as $id) {
						try {
							MetaFile::getInstance(NULL, (int) $id)->delete();
						}
						catch(MetaFileException $e) {
							$success = FALSE;
							$errorMsg = 'Fehler beim Löschen der Datei(en)!';
						}
					}
				}

				$this->getArticleCategories();
				try {
					$this->uploadArticleFile($article, array('file_description' => $_POST['file_description']));
				}
				catch(MetaFileException $e) {
					$success = FALSE;
					$errorMsg = 'Fehler beim Upload der Datei!';
				}

				$rows = array();

				foreach(MetaFile::getFilesForReference($article->getId(), 'articles', 'sortByCustomSort') as $mf) {
					$rows[] = array(
						'id'		=> $mf->getId(),
						'filename'	=> $mf->getFilename(),
						'isThumb'	=> $mf->isWebImage(),

						// notice: Loading in iframe document doesn't allow markup due to intrinsic entity encoding

						'type'		=> $mf->isWebImage() ? $this->getThumbPath($mf) : $mf->getMimetype(),
						'metadata'	=> $mf->getData()
					);
				}

				return array('success' => $success, 'message' => $errorMsg, 'files' => $rows);

			case 'sortFiles':
				if(count($this->validatedRequests['sortOrder']) > 1) {
					$filesSorted = $article->getReferencingFiles();

					$oldPos = 0;

					foreach($filesSorted as $file) {
						$newPos = array_search($oldPos++, $this->validatedRequests['sortOrder']);

						$this->db->preparedExecute("
							UPDATE
								files
							SET
								customSort = ?
							WHERE
								filesID = ?
						", array((int) $newPos, $file->getId()));
					}
				}
		}
	}
}
?>