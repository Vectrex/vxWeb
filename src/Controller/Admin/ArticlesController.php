<?php

namespace App\Controller\Admin;

use vxPHP\Util\Rex;

use vxPHP\Image\ImageModifierFactory;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Controller\Controller;

use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;

use vxWeb\Model\MetaFile\MetaFile;
use vxWeb\Model\Article\ArticleQuery;
use vxWeb\Model\Article\Article;
use vxWeb\Model\Article\Exception\ArticleException;
use vxWeb\Model\ArticleCategory\ArticleCategory;
use vxWeb\Model\ArticleCategory\Exception\ArticleCategoryException;

use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;

use vxPHP\Application\Application;
use vxPHP\Routing\Router;
use vxPHP\Webpage\MenuGenerator;
use vxPHP\Database\Util;
use vxPHP\Constraint\Validator\Date;
use vxPHP\Application\Locale\Locale;
use vxPHP\Constraint\Validator\RegularExpression;
use vxWeb\Model\ArticleCategory\ArticleCategoryQuery;

class ArticlesController extends Controller {

	protected function execute() {

		$admin = Application::getInstance()->getCurrentUser();
		$redirectUrl = Router::getRoute('articles', 'admin.php')->getUrl();
		$action = $this->route->getPathParameter('action');
		
		if($action === 'filter') {
			
			return $this->filterArticlesList();
			
		}
		
		// editing something?

		if(($id = $this->request->query->get('id'))) {

			try {
				$article = Article::getInstance($id);
			}
			catch(ArticleException $e) {
				return $this->redirect($redirectUrl);
			}

			// check permission of non superadmin

			if(!$admin->hasRole('superadmin') && $admin->getAttribute('id') != $article->getCreatedById()) {
				return $this->redirect($redirectUrl);
			}
		}

		// delete article

		if(isset($article) && $action === 'del') {

			try {
				$article->delete();
			}
			catch(ArticleException $e) { }

			return $this->redirect($redirectUrl);
		}

		// edit or add article

		if(isset($article) || $action === 'new') {
			
			MenuGenerator::setForceActiveMenu(TRUE);

			// fill category related properties - replacing default method allows user privilege considerations

			$articleForm = $this->buildEditForm();

			if(isset($article)) {

				$articleForm->setInitFormValues([
					'articlecategoriesid'	=> $article->getCategory()->getId(),
					'headline'				=> $article->getHeadline(),
					'customsort'			=> $article->getCustomSort(),
					'teaser'				=> $article->getData('teaser'),
					'content'				=> htmlspecialchars($article->getData('content'), ENT_NOQUOTES, 'UTF-8'),
					'article_date'			=> is_null($article->getDate())			? '' : $article->getDate()->format('d.m.Y'),
					'display_from'			=> is_null($article->getDisplayFrom())	? '' : $article->getDisplayFrom()->format('d.m.Y'),
					'display_until'			=> is_null($article->getDisplayUntil())	? '' : $article->getDisplayUntil()->format('d.m.Y')
				]);

				$submitLabel = 'Änderungen übernehmen';

			}

			else {

				$article = new Article();
				$submitLabel = 'Artikel anlegen';
				$articleForm->initVar('is_add', 1);
			}

			$articleForm->addElement(FormElementFactory::create('button', 'submit_article')->setInnerHTML($submitLabel));

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

		$restrictingWhere = $admin->hasRole('superadmin') ? '1 = 1' : ('createdBy = ' . (int) $admin->getAttribute('id'));

		return new Response(
			SimpleTemplate::create('admin/articles_list.php')
				->assign('can_publish', $admin->hasRole('superadmin'))
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
	
	private function filterArticlesList() {

		$db = Application::getInstance()->getDb();
		$filter = $this->request->request->get('filter');

		$query = ArticleQuery::create($db);
		
		if(trim($filter['title'])) {
			
			$query->where('headline LIKE ?', ['%' . trim($filter['title']) . '%']);
			
		}
		
		if(trim($filter['category'])) {
			
			$categories = ArticleCategoryQuery::create($db)->where('title LIKE ?', ['%' . trim($filter['category']) . '%'])->select();

			if(count($categories)) {
				$query->filterByCategories($categories);
			}
			
		}

		$canPublish = Application::getInstance()->getCurrentUser()->hasRole('superadmin');
		$tpl = SimpleTemplate::create('admin/snippets/article_row.php');
		$markup = [];

		foreach($query->select() as $article) {
			$markup[] = $tpl
				->assign('article', $article)
				->assign('can_publish', $canPublish)
				->assign('color', 0)
				->display();
		}

		return new JsonResponse(['rows' => $markup]);
		
	}
	
	protected function xhrPublish() {
		
		$id = $this->request->request->getInt('id');
		$state = $this->request->request->getInt('state');
		$admin = Application::getInstance()->getCurrentUser();
		
		try {
			if($id && $article = Article::getInstance($id)) {
				if($state) {
					
					// publish logs publishedById
					
					$article->publish($admin->getAttribute('id'))->save();
				}
				else {
					
					// unpublish sets publishedById to NULL

					$article->unpublish()->save();
				}
				return new JsonResponse(['success' => TRUE]);
			}
		}
		catch(\Exception $e) {
			return new JsonResponse(['success' => FALSE, 'error' => $e->getMessage()]);
		}
	}

	protected function xhrExecute() {

		// id comes either via URL or as an extra form field

		$id = $this->request->query->get('id', $this->request->request->get('id'));
		$admin = Application::getInstance()->getCurrentUser();

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

				$form = $this->buildEditForm();
						
				// @todo allow CSRF token; currently the action mismatch between initial form and XHR form request prevents checking

				$v = $form
					->disableCsrfToken()
					->bindRequestParameters($this->request->request)
					->validate()
					->getValidFormValues();

				if($v['article_date'] != '') {
					$article->setDate(new \DateTime(Util::unFormatDate($v['article_date'], 'de')));
				}
				else {
					$article->setDate();
				}

				if($v['display_from'] != '') {
					$article->setDisplayFrom(new \DateTime(Util::unFormatDate($v['display_from'], 'de')));
				}
				else {
					$article->setDisplayFrom();
				}

				if($v['display_until'] != '') {
					$article->setDisplayUntil(new \DateTime(Util::unFormatDate($v['display_until'], 'de')));
				}
				else {
					$article->setDisplayUntil();
				}

				$errors	= $form->getFormErrors();

				if(!empty($errors)) {
					$texts	= $form->getErrorTexts();

					$response = [];
					foreach(array_keys($errors) as $err) {
						$response[] = ['name' => $err, 'error' => 1, 'errorText' => isset($texts[$err]) ? $texts[$err] : NULL];
					}
					return new JsonResponse(['elements' => $response]);
				}

				try {

					// validate submitted category id - replacing default method allows user privilege considerations

					$article->setCategory($this->validateArticleCategory(ArticleCategory::getInstance($v['articlecategoriesid'])));
					$article->setHeadline($v['headline']);
					$article->setData(['teaser' => $v['teaser'], 'content' => $v['content']]);
					$article->setCustomSort($v['customsort']);

					$id = $article->getId();
					
					if(!$id) {
						$article->setCreatedById($admin->getAttribute('id'));
					}
					else {
						$article->setUpdatedById($admin->getAttribute('id'));
					}

					if($article->wasChanged()) {
						$article->save();
						if(!$id) {
							return new JsonResponse([
								'success' => TRUE,
								'id' => $article->getId()
							]);
						}
						else {
							return new JsonResponse(['success' => TRUE]);
						}
					}

					else {
						return new JsonResponse(['success' => TRUE, 'message' =>'Keine Änderung, nichts gespeichert!']);
					}
				}
				catch(ArticleException $e) {
					return new JsonResponse(['message' => 'Beim Anlegen/Aktualisieren des Artikels ist ein Fehler aufgetreten!']);
				}
				catch(ArticleCategoryException $e) {
					return new JsonResponse(['message' => 'Beim Anlegen/Aktualisieren des Artikels ist ein Fehler aufgetreten!']);
				}

			case 'sortFiles':
				
				$article->setCustomSortOfMetaFile(
					MetaFile::getInstance(NULL, $this->request->request->getInt('file')),
					$this->request->request->getInt('to')
				);
				
				$article->save();
				break;
				
				
			case 'getFiles':

				$rows = [];

				foreach(Article::getInstance($this->request->request->getInt('articlesId'))->getLinkedMetaFiles() as $mf) {
					$rows[] = [
						'id'		=> $mf->getId(),
						'folderid'	=> $mf->getMetaFolder()->getId(),
						'filename'	=> $mf->getFilename(),
						'isThumb'	=> $mf->isWebImage(),
						'type'		=> $mf->isWebImage() ? $this->getThumbPath($mf) : $mf->getMimetype(),
						'path'		=> $mf->getMetaFolder()->getRelativePath()
					];
				}

				return new JsonResponse(['files' => $rows]);

				break;
				
		}

		return new JsonResponse();

	}

	/**
	 * @param ArticleCategory $cat
	 * @return ArticleCategory
	 */
	private function validateArticleCategory(ArticleCategory $cat) {
		return $cat;
	}

	/**
	 * build edit form
	 * 
	 * @return \vxPHP\Form\HtmlForm
	 */
	private function buildEditForm() {

		$categories = ['-1' => 'Bitte Kategorie wählen...'];
		
		foreach(ArticleCategory::getArticleCategories('sortByCustomSort') as $c) {
			$categories[$c->getId()] = $c->getTitle();
		}

		return HtmlForm::create('admin_edit_article.htm')
			->setAttribute('class', 'editArticleForm')
			->addElement(FormElementFactory::create('select', 'articlecategoriesid', NULL, [], $categories, TRUE, [], [new RegularExpression(Rex::INT_EXCL_NULL)]))
			->addElement(FormElementFactory::create('input', 'headline', NULL, [], [], TRUE, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
			->addElement(FormElementFactory::create('textarea', 'teaser', NULL, [], [], FALSE, ['trim', 'strip_tags']))
			->addElement(FormElementFactory::create('textarea', 'content', NULL, [], [], TRUE, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
			->addElement(FormElementFactory::create('input', 'article_date', NULL, [], [], FALSE, ['trim'], [new Date(['locale' => new Locale('de')])]))
			->addElement(FormElementFactory::create('input', 'display_from', NULL, [], [], FALSE, ['trim'], [new Date(['locale' => new Locale('de')])]))
			->addElement(FormElementFactory::create('input', 'display_until', NULL, [], [], FALSE, ['trim'], [new Date(['locale' => new Locale('de')])]))
			->addElement(FormElementFactory::create('input', 'customsort', NULL, [], [], FALSE, ['trim'], [new RegularExpression(Rex::EMPTY_OR_INT_EXCL_NULL)]));

	}

	/**
	 * @param MetaFile $f
	 * @return string
	 */
	private function getThumbPath(MetaFile $f) {

		// check and - if required - generate thumbnail

		$fi			= $f->getFileInfo();
		$actions	= ['crop 1', 'resize 0 40'];
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
					call_user_func_array([$imgEdit, $method], $params);
				}
			}

			$imgEdit->export($dest);
		}

		return str_replace(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR), '', $dest);
	}

}
