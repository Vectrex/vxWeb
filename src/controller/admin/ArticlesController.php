<?php

use vxPHP\Util\Rex;
use vxPHP\Util\Uuid;

use vxPHP\Image\ImageModifierFactory;
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
use vxPHP\User\User;
use vxPHP\Database\vxPDOUtil;
use vxPHP\Routing\Router;

class ArticlesController extends Controller {

	protected function execute() {

		$admin			= User::getSessionUser();
		$redirectUrl	= Router::getRoute('articles', 'admin.php')->getUrl();

		// editing something?

		if(($id = $this->request->query->get('id'))) {

			try {
				$article = Article::getInstance($id);
			}
			catch(ArticleException $e) {
				return $this->redirect($redirectUrl);
			}

			// check permission of non superadmin

			if(!$admin->hasSuperAdminPrivileges() && $admin->getAdminId() != $article->getCreatedBy()->getAdminId()) {
				return $this->redirect($redirectUrl);
			}
		}

		// delete article

		if(isset($article) && count($this->pathSegments) > 1 && $this->pathSegments[1] == 'del') {

			try {
				$article->delete();
			}
			catch(ArticleException $e) { }

			return $this->redirect($redirectUrl);
		}

		// edit or add article

		if(isset($article) || count($this->pathSegments) > 1 && $this->pathSegments[1] == 'new') {

			// fill category related properties - replacing default method allows user privilege considerations

			$categories = $this->getArticleCategories();

			$articleForm = HtmlForm::create('admin_edit_article.htm')->setAttribute('class', 'editArticleForm');

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

				$submitLabel = 'Änderungen übernehmen';

			}

			else {

				$article = new Article();
				$submitLabel = 'Artikel anlegen';
				$articleForm->initVar('is_add', 1);
			}

			$articleForm
				->addElement(FormElementFactory::create('select', 'articlecategoriesID', NULL, array('size' => 1, 'class' => 'xxl'), $categories, FALSE, array(), array(Rex::INT_EXCL_NULL)))
				->addElement(FormElementFactory::create('input', 'Headline', NULL, array('maxlength' => 200, 'class' => 'xxl'), array(), FALSE, array('trim'), array(Rex::NOT_EMPTY_TEXT)))
				->addElement(FormElementFactory::create('textarea', 'Teaser', NULL, array('rows' => 3, 'cols' => 40, 'class' => 'xxl'), array(), FALSE, array('trim', 'strip_tags')))
				->addElement(FormElementFactory::create('textarea', 'Content', NULL, array('rows' => 10, 'cols' => 40, 'class' => 'xxl'), array(), FALSE, array('trim'), array(Rex::NOT_EMPTY_TEXT)))
				->addElement(FormElementFactory::create('input', 'Article_Date', NULL, array('maxlength' => 10, 'class' => 'm'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('input', 'Display_from', NULL, array('maxlength' => 10, 'class' => 'm'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('input', 'Display_until', NULL, array('maxlength' => 10, 'class' => 'm'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('input', 'customSort', NULL, array('maxlength' => 4, 'class' => 's'), array(), FALSE, array('trim'), array(Rex::EMPTY_OR_INT_EXCL_NULL)))
				->addElement(FormElementFactory::create('button', 'submit_article', '', array('type' => 'submit'))->setInnerHTML($submitLabel));

			$articleForm->bindRequestParameters();

			$uploadMaxFilesize = min(
					$this->toBytes(ini_get('upload_max_filesize')),
					$this->toBytes(ini_get('post_max_size'))
			);
			$maxExecutionTime = ini_get('max_execution_time');
				
			return new Response(
				SimpleTemplate::create('admin/articles_edit.php')
					->assign('title', $article->getHeadline())
					->assign('backlink', $this->pathSegments[0])
					->assign('article_form', $articleForm->render())
					->assign('upload_max_filesize',		$uploadMaxFilesize)
					->assign('max_execution_time_ms',	$maxExecutionTime * 900) // 10pct "safety margin"
					->display()
			);
		}

		$restrictingWhere = $admin->hasSuperAdminPrivileges() ? '1 = 1' : ('createdBy = ' . $admin->getAdminId());

		return new Response(
			SimpleTemplate::create('admin/articles_list.php')
				->assign('page', $this->pathSegments[0])
				->assign('can_publish', $admin->hasSuperAdminPrivileges())
				->assign('articles', ArticleQuery::create(Application::getInstance()->getDb())
					->where($restrictingWhere)
					->sortBy('lastUpdated', FALSE)
					->select())
				->display()
		);
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
	
	protected function xhrPublish() {
		
		$id = $this->request->request->getInt('id');
		$state = $this->request->request->getInt('state');
		
		try {
			if($id && $article = Article::getInstance($id)) {
				if($state) {
					$article->publish()->save();
				}
				else {
					$article->unpublish()->save();
				}
				return new JsonResponse(array('success' => TRUE));
			}
		}
		catch(\Exception $e) {
			return new JsonResponse(array('success' => FALSE, 'error' => $e->getMessage()));
		}
	}

	protected function xhrExecute() {

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

		else {
			$article = new Article();
		}

		switch($this->request->request->get('httpRequest')) {

			// check article data

			case 'checkForm':

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
						$article->setDate(new \DateTime(vxPDOUtil::unFormatDate($v['Article_Date'], 'de')));
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
						$article->setDisplayFrom(new \DateTime(vxPDOUtil::unFormatDate($v['Display_from'], 'de')));
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
						$article->setDisplayUntil(new \DateTime(vxPDOUtil::unFormatDate($v['Display_until'], 'de')));
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

					$article->setCreatedBy(User::getSessionUser());
					$article->setUpdatedBy(User::getSessionUser());

					$id = $article->getId();

					if($article->wasChanged()) {
						$article->save();
						if(!$id) {
							return new JsonResponse(array(
								'success' => TRUE,
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

			case 'sortFiles':
				
				$article->setCustomSortOfMetaFile(
					MetaFile::getInstance(NULL, $this->request->request->getInt('file')),
					$this->request->request->getInt('to')
				);
				
				$article->save();
				break;
				
				
			case 'getFiles':

				$rows = array();

				foreach(Article::getInstance($this->request->request->getInt('articlesId'))->getLinkedMetaFiles() as $mf) {
					$rows[] = array(
						'id'		=> $mf->getId(),
						'folderId'	=> $mf->getMetaFolder()->getId(),
						'filename'	=> $mf->getFilename(),
						'isThumb'	=> $mf->isWebImage(),
						'type'		=> $mf->isWebImage() ? $this->getThumbPath($mf) : $mf->getMimetype(),
						'path'		=> $mf->getMetaFolder()->getRelativePath()
					);
				}

				return new JsonResponse(array('files' => $rows));

				break;
				
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

		return str_replace(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR), '', $dest);
	}

}
