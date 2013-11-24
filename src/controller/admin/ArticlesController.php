<?php

use vxPHP\Util\Rex;
use vxPHP\Util\Uuid;

use vxPHP\User\Admin;

use vxPHP\Template\SimpleTemplate;

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
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Application\Application;

class ArticlesController extends Controller {

	public function execute() {

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

				$filesForm = $this->initFilesForm($article);
				$filesForm->bindRequestParameters();

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

			if($articleForm->wasSubmittedByName('submit_article')) {

				if($this->validateFormAndSaveArticle($articleForm, $article) === TRUE) {
					$this->redirect('articles', NULL, array('id' => $article->getId()));
				}

			}

			if(isset($filesForm) && $filesForm->wasSubmittedByName('submit_file')) {
				$vals	= $filesForm->getValidFormValues();

				if(isset($vals['delete_file'])) {
					foreach($vals['delete_file'] as $k => $v) {
						if($v == 1) {
							MetaFile::getInstance(NULL, (int) $k)->delete();
						}
					}
				}
				vxWeb\FileUtil::uploadFileForArticle($article, $vals);
				$this->redirect('articles', NULL, array('id' => $article->getId()));
			}

			return new Response(
				SimpleTemplate::create('admin/articles_edit.php')
					->assign('backlink', $this->pathSegments[0])
					->assign('article_form', $articleForm->render())
					->assign('files_form', isset($filesForm) ? $filesForm->render() : '')
					->display()
			);
		}

		$admin = Admin::getInstance();
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

	private function validateFormAndSaveArticle(HtmlForm $form, Article $article) {

		$v = $form->validate()->getValidFormValues();
		$db = Application::getInstance()->getDb();


		if($v['Article_Date'] != '') {
			if(!DateTime::checkDate($v['Article_Date'])) {
				$form->setError('Article_Date');
			}
			else {
				$article->setDate(new \DateTime($db->formatDate($v['Article_Date'], 'de')));
			}
		}

		if($v['Display_from'] != '') {
			if(!DateTime::checkDate($v['Display_from'])) {
				$form->setError('Display_from');
			}
			else {
				$article->setDisplayFrom(new \DateTime($db->formatDate($v['Display_from'], 'de')));
			}
		}

		if($v['Display_until'] != '') {
			if(!DateTime::checkDate($v['Display_until'])) {
				$form->setError('Display_until');
			}
			else {
				$article->setDisplayUntil(new \DateTime($db->formatDate($v['Display_until'], 'de')));
			}
		}

		if(!$form->getFormErrors()) {

			try {

				// validate submitted category id - replacing default method allows user privilege considerations

				$article->setCategory($this->validateArticleCategory(ArticleCategory::getInstance($v['articlecategoriesID'])));
				$article->setHeadline($v['Headline']);
				$article->setData(array('Teaser' => $v['Teaser'], 'Content' => $v['Content']));
				$article->setCustomSort($v['customSort']);

				if($article->wasChanged()) {
					$article->save();
				}

				return TRUE;

			}

			catch(ArticleException $e) {
				$form->setError('system');
			}
			catch(ArticleCategoryException $e) {
				$form->setError('system');
			}
		}

		return FALSE;

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
}
