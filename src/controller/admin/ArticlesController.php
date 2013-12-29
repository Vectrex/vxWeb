<?php

use vxPHP\Util\Rex;
use vxPHP\Util\Uuid;

use vxPHP\User\Admin;
use vxPHP\Image\ImageModifier;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Controller\Controller;

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
use vxPHP\Orm\Custom\ArticleQuery;

use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;


use vxPHP\Application\Application;

class ArticlesController extends Controller {

	protected function execute() {

		$admin = Admin::getInstance();

		// editing something?

		if(($id = $this->request->query->get('id'))) {

			try {
				$article = Article::getInstance($id);
			}
			catch(ArticleException $e) {
				$this->redirect('articles');
			}

			// check permission of non superadmin

			if(!$admin->hasSuperAdminPrivileges() && $admin->getAdminId() != $article->getCreatedBy()->getAdminId()) {
				$this->redirect('articles');
			}
		}

		// delete article

		if(isset($article) && count($this->pathSegments) > 1 && $this->pathSegments[1] == 'del') {

			$files = $article->getReferencingFiles();

			try {
				$article->delete();
			}
			catch(ArticleException $e) {
				$this->redirect('articles');
			}
			foreach($files as $f) {
				$f->delete();
			}
			$this->redirect('articles');
		}

		// edit or add article

		if(isset($article) || count($this->pathSegments) > 1 && $this->pathSegments[1] == 'new') {

			// fill category related properties - replacing default method allows user privilege considerations

			$categories = $this->getArticleCategories();

			$articleForm = HtmlForm::create('admin_edit_article.htm')->setAttribute('class', 'editArticleForm');

			$submitButton = FormElementFactory::create('button', 'submit_article', '', array('type' => 'submit', 'class' => 'l'));

			if(isset($article)) {

				$data = $article->getData();

				$articleForm->setInitFormValues(array(
					'articlecategoriesID'	=> $article->getCategory()->getId(),
					'Headline'				=> $article->getHeadline(),
					'customSort'			=> $article->getCustomSort(),
					'Teaser'				=> $data['Teaser'],
					'Content'				=> isset($data['Content']) ? htmlspecialchars($data['Content'], ENT_NOQUOTES, 'UTF-8') : '',
					'Article_Date'			=> is_null($article->getDate())			? '' : $article->getDate()->format('d.m.Y'),
					'Display_from'			=> is_null($article->getDisplayFrom())	? '' : $article->getDisplayFrom()->format('d.m.Y'),
					'Display_until'			=> is_null($article->getDisplayUntil())	? '' : $article->getDisplayUntil()->format('d.m.Y')
				));

				$submitButton->setInnerHTML('Änderungen übernehmen');

				$filesForm = $this->initFilesForm($article)->bindRequestParameters();

			}

			else {

				$article = new Article();

				$submitButton->setInnerHTML('Newsmeldung anlegen');
				$articleForm->initVar('is_add', 1);
			}

			$articleForm
				->addElement($submitButton)
				->addElement(FormElementFactory::create('select', 'articlecategoriesID', NULL, array('size' => 1, 'class' => 'xxl'), $categories, FALSE, array(), array(Rex::INT_EXCL_NULL)))
				->addElement(FormElementFactory::create('input', 'Headline', NULL, array('maxlength' => 200, 'class' => 'xxl'), array(), FALSE, array('trim'), array(Rex::NOT_EMPTY_TEXT)))
				->addElement(FormElementFactory::create('textarea', 'Teaser', NULL, array('rows' => 3, 'cols' => 40, 'class' => 'xxl'), array(), FALSE, array('trim', 'strip_tags')))
				->addElement(FormElementFactory::create('textarea', 'Content', NULL, array('rows' => 10, 'cols' => 40, 'class' => 'xxl'), array(), FALSE, array('trim'), array(Rex::NOT_EMPTY_TEXT)))
				->addElement(FormElementFactory::create('input', 'Article_Date', NULL, array('maxlength' => 10, 'class' => 'm'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('input', 'Display_from', NULL, array('maxlength' => 10, 'class' => 'm'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('input', 'Display_until', NULL, array('maxlength' => 10, 'class' => 'm'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('input', 'customSort', NULL, array('maxlength' => 4, 'class' => 's'), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT_EXCL_NULL)));

			$articleForm->bindRequestParameters();

			return new Response(
				SimpleTemplate::create('admin/articles_edit.php')
					->assign('backlink', $this->pathSegments[0])
					->assign('article_form', $articleForm->render())
					->assign('files_form', isset($filesForm) ? $filesForm->render() : '')
					->display()
			);
		}

		$restrictingWhere = $admin->hasSuperAdminPrivileges() ? '1 = 1' : ('createdBy = ' . $admin->getAdminId());

		return new Response(
			SimpleTemplate::create('admin/articles_list.php')
				->assign('page', $this->pathSegments[0])
				->assign('articles', ArticleQuery::create(Application::getInstance()->getDb())
					->where($restrictingWhere)
					->sortBy('lastUpdated', FALSE)
					->select())
				->display()
		);
	}

	protected function xhrExecute() {

		$db = Application::getInstance()->getDb();

		// id comes either via URL or as an extra form field

		$id = $this->request->query->get('id', $this->request->request->get('id'));

		if($id) {

			try {
				$article = Article::getInstance($id);
			}
			catch(ArticleException $e) {
				return new JsonResponse();
			}

		}

		else if(($elements = $this->request->request->get('elements')) && isset($elements['id'])) {
			try {
				$article = Article::getInstance($elements['id']);
			}
			catch(ArticleException $e) {
				return new JsonResponse();
			}
		}

		else {
			$article = new Article();
		}

		switch($this->request->request->get('httpRequest')) {

			// check article data

			case 'checkForm':

				$this->request->request->add($this->request->request->get('elements'));

				$form = HtmlForm::create()
					->addElement(FormElementFactory::create('select', 'articlecategoriesID', NULL, array(), array(), FALSE, array(), array(Rex::INT_EXCL_NULL)))
					->addElement(FormElementFactory::create('input', 'Headline', NULL, array(), array(), FALSE, array('trim'), array(Rex::NOT_EMPTY_TEXT)))
					->addElement(FormElementFactory::create('textarea', 'Teaser', NULL, array(), array(), FALSE, array('trim', 'strip_tags')))
					->addElement(FormElementFactory::create('textarea', 'Content', NULL, array(), array(), FALSE, array('trim'), array(Rex::NOT_EMPTY_TEXT)))
					->addElement(FormElementFactory::create('input', 'Article_Date', NULL, array(), array(), FALSE, array('trim')))
					->addElement(FormElementFactory::create('input', 'Display_from', NULL, array(), array(), FALSE, array('trim')))
					->addElement(FormElementFactory::create('input', 'Display_until', NULL, array(), array(), FALSE, array('trim')))
					->addElement(FormElementFactory::create('input', 'customSort', NULL, array(), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT_EXCL_NULL)));

				$v = $form
					->bindRequestParameters($this->request->request)
					->validate()
					->getValidFormValues();

				if($v['Article_Date'] != '') {
					if(!DateTime::checkDate($v['Article_Date'])) {
						$form->setError('Article_Date');
					}
					else {
						$article->setDate(new \DateTime($db->formatDate($v['Article_Date'], 'de')));
					}
				}
				else {
					$article->setDate();
				}

				if($v['Display_from'] != '') {
					if(!DateTime::checkDate($v['Display_from'])) {
						$form->setError('Display_from');
					}
					else {
						$article->setDisplayFrom(new \DateTime($db->formatDate($v['Display_from'], 'de')));
					}
				}
				else {
					$article->setDisplayFrom();
				}

				if($v['Display_until'] != '') {
					if(!DateTime::checkDate($v['Display_until'])) {
						$form->setError('Display_until');
					}
					else {
						$article->setDisplayUntil(new \DateTime($db->formatDate($v['Display_until'], 'de')));
					}
				}
				else {
					$article->setDisplayUntil();
				}

				$errors	= $form->getFormErrors();

				if(!empty($errors)) {
					$texts	= $form->getErrorTexts();

					$response = array();
					foreach(array_keys($errors) as $err) {
						$response[] = array('name' => $err, 'error' => 1, 'errorText' => isset($texts[$err]) ? $texts[$err] : NULL);
					}
					return new JsonResponse(array('elements' => $response));
				}

				try {

					// validate submitted category id - replacing default method allows user privilege considerations

					$article->setCategory($this->validateArticleCategory(ArticleCategory::getInstance($v['articlecategoriesID'])));
					$article->setHeadline($v['Headline']);
					$article->setData(array('Teaser' => $v['Teaser'], 'Content' => $v['Content']));
					$article->setCustomSort($v['customSort']);

					$id = $article->getId();

					if($article->wasChanged()) {
						$article->save();
						if(!$id) {
							return new JsonResponse(array(
								'success' => TRUE,
								'markup' => array('html' => $this->initFilesForm($article)->render()),
								'id' => $article->getId()
							));
						}
						else {
							return new JsonResponse(array('success' => TRUE));
						}
					}

					else {
						return new JsonResponse(array('success' => TRUE, 'message' =>'Keine Änderung, nichts gespeichert!'));
					}
				}
				catch(ArticleException $e) {
					return new JsonResponse(array('message' => 'Beim Anlegen/Aktualisieren des Artikels ist ein Fehler aufgetreten!'));
				}
				catch(ArticleCategoryException $e) {
					return new JsonResponse(array('message' => 'Beim Anlegen/Aktualisieren des Artikels ist ein Fehler aufgetreten!'));
				}

			case 'ifuSubmit':

				$success = TRUE;
				$errorMsg = '';

				if(($delIds = $this->request->request->get('delete_file'))) {
					foreach(array_keys($delIds) as $id) {
						try {
							MetaFile::getInstance(NULL, (int) $id)->delete();
						}
						catch(MetaFileException $e) {
							$success = FALSE;
							$errorMsg = 'Fehler beim Löschen der Datei(en)!';
						}
					}
				}

//				$this->getArticleCategories();
				try {
					vxWeb\FileUtil::uploadFileForArticle($article, array('file_description' => $this->request->request->get('file_description')));
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

				return new JsonResponse(array('success' => $success, 'message' => $errorMsg, 'files' => $rows));

			case 'sortFiles':
				if(($sortOrder = $this->request->request->get('sortOrder')) && count($sortOrder) > 1) {

					$filesSorted	= $article->getReferencingFiles();
					$oldPos			= 0;

					foreach($filesSorted as $file) {
						$newPos = array_search($oldPos++, $sortOrder);

						$db->preparedExecute("
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

		return new JsonResponse();

	}

	/**
	 * @return array
	 */
	private function getArticleCategories() {
		$categories = array('-1' => 'Bitte Kategorie wählen...');

		foreach(ArticleCategory::getArticleCategories('sortByCustomSort') as $c) {
			$categories[$c->getId()] = $c->getTitle();
		}

		return $categories;
	}

	/**
	 * @param ArticleCategory $cat
	 * @return ArticleCategory
	 */
	private function validateArticleCategory(ArticleCategory $cat) {
		return $cat;
	}

	/**
	 * @param Article $article
	 * @return HtmlForm
	 */
	private function initFilesForm(Article $article) {

		$cbValues		= array();
		$mimetypes		= array();
		$descriptions	= array();
		$filenames		= array();

		$form = HtmlForm::create('admin_edit_article_files.htm')
			->setEncType('multipart/form-data')
			->setAttribute('class', 'editArticleForm');

		if(!is_null($article->getId())) {
			$form->addElement(FormElementFactory::create('input', 'id', $article->getId(), array('type' => 'hidden')));

			foreach($article->getReferencingFiles() as $f) {

				$data = $f->getData();

				$cbValues[$data['filesID']]	= 1;
				$descriptions[]				= $data['Description'];
				$filenames[]				= $data['File'];
				$mimetypes[]				= $f->isWebImage() ? ('<img class="thumb" src="' . $f->getRelativePath() . '#crop 1|resize 0 40" alt="">') : $f->getMimetype();
			}
		}

		$fileSubmit = FormElementFactory::create('button', 'submit_file', '', array('type' => 'submit'));
		$fileSubmit->setInnerHTML('Datei(en) hinzufügen/löschen');

		return $form
			->initVar	('files_count',		count($filenames))
			->addMiscHtml('mimetypes',		$mimetypes)
			->addMiscHtml('descriptions',	$descriptions)
			->addMiscHtml('filenames',		$filenames)
			->addElementArray(FormElementFactory::create('checkbox', 'delete_file', $cbValues))
			->addElement(FormElementFactory::create('input', 'upload_file', '', array('type' => 'file')))
			->addElement(FormElementFactory::create('textarea', 'file_description', '', array('rows' => 2, 'cols' => 40, 'class' => 'xl')))
			->addElement($fileSubmit);
	}

	/**
	 * @param MetaFile $f
	 * @return string
	 */
	private function getThumbPath(MetaFile $f) {

		// check and - if required - generate thumbnail

		$fi			= $f->getFileInfo();
		$actions	= array('crop 1', 'resize 0 40');
		$dest		=
			$f->getMetaFolder()->getFilesystemFolder()->createCache() .
			$fi->getFilename() .
			'@' .
			implode('|', $actions) .
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

		return str_replace(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR), '', $dest);
	}


}