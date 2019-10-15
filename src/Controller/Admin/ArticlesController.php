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

	    $app = Application::getInstance();
		$admin = $app->getCurrentUser();
		$redirectUrl = $app->getRouter()->getRoute('articles', 'admin.php')->getUrl();
		$action = $this->route->getPathParameter('action');
		
		if($action === 'list') {
			return $this->createArticlesList($this->request->request->get('filter'));
		}

		// editing something?

		else if(($id = $this->request->query->get('id'))) {

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
			
			MenuGenerator::setForceActiveMenu(true);

			// fill category related properties - replacing default method allows user privilege considerations

			$articleForm = $this->buildEditForm();

			if(isset($article)) {

				$articleForm->setInitFormValues([
					'articlecategoriesid'	=> $article->getCategory()->getId(),
					'headline'				=> $article->getHeadline(),
					'subline'               => $article->getData('subline'),
					'customsort'			=> $article->getCustomSort(),
					'teaser'				=> $article->getData('teaser'),
					'content'				=> htmlspecialchars($article->getData('content'), ENT_NOQUOTES, 'UTF-8'),
					'article_date'			=> is_null($article->getDate())			? '' : $article->getDate()->format('d.m.Y'),
					'display_from'			=> is_null($article->getDisplayFrom())	? '' : $article->getDisplayFrom()->format('d.m.Y'),
					'display_until'			=> is_null($article->getDisplayUntil())	? '' : $article->getDisplayUntil()->format('d.m.Y')
				]);

                $articleForm->getElementsByName('customflags')->setChecked($article->getCustomFlags());

				$submitLabel = 'Änderungen übernehmen';

			}

			else {

				$article = new Article();
				$submitLabel = 'Artikel anlegen';
				$articleForm->initVar('is_add', 1);
			}

			$articleForm->addElement(FormElementFactory::create('button', 'submit_article')->setInnerHTML($submitLabel));

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

		$categories = ArticleCategoryQuery::create(Application::getInstance()->getDb())->sortBy('alias')->select();
		return new Response(SimpleTemplate::create('admin/articles_list.php')->assign('categories', $categories)->display());

	}

    /**
     * simple helper function to convert ini values like 10M or 256K to integer
     *
     * @param string $val
     * @return int|string
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
	
	private function createArticlesList(array $filter = [], array $sort = null) {

		$admin = Application::getInstance()->getCurrentUser();
		$db = Application::getInstance()->getDb();

		// restrict list to articles which were created by user

		$query = ArticleQuery::create($db)
			->where($admin->hasRole('superadmin') ? '1 = 1' : ('createdBy = ' . (int) $admin->getAttribute('id')))
		;

		// add sorting

		if($sort) {
			if($sort['column'] !== 'category') {
				$query->sortBy($sort['column'], $sort['asc']);
			}
			else {
			}
		}
		else {
			$query->sortBy('lastUpdated', false);
		}
		
		// apply filter for title

		if(isset($filter['title']) && trim($filter['title'])) {

			$query->where('headline LIKE ?', ['%' . trim($filter['title']) . '%']);

		}
		
		// apply filter for a category name
		
		if(isset($filter['category'])) {
			
			try {
				$query->filterByCategory(ArticleCategory::getInstance($filter['category']));
			}
			catch(ArticleCategoryException $e) {}

		}

		$tpl = SimpleTemplate::create('admin/snippets/article_row.php');
		$markup = [];
		$canPublish = $admin->hasRole('superadmin');

		foreach($query->select() as $article) {
			$markup[] = $tpl
				->assign('article', $article)
				->assign('can_publish', $canPublish)
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
					
					// unpublish sets publishedById to null

					$article->unpublish()->save();
				}
				return new JsonResponse(['success' => true]);
			}
		}
		catch(\Exception $e) {
			return new JsonResponse(['success' => false, 'error' => $e->getMessage()]);
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
					->getValidFormValues()
                ;

				if($v['article_date'] !== '') {
					$article->setDate(new \DateTime(Util::unFormatDate($v['article_date'], 'de')));
				}
				else {
					$article->setDate();
				}

				if($v['display_from'] !== '') {
					$article->setDisplayFrom(new \DateTime(Util::unFormatDate($v['display_from'], 'de')));
				}
				else {
					$article->setDisplayFrom();
				}

				if($v['display_until'] !== '') {
					$article->setDisplayUntil(new \DateTime(Util::unFormatDate($v['display_until'], 'de')));
				}
				else {
					$article->setDisplayUntil();
				}

				$errors	= $form->getFormErrors();

				if(!empty($errors)) {
					$response = [];
                    foreach($errors as $element => $error) {
                        $response[] = ['name' => $element, 'error' => 1, 'errorText' => $error->getErrorMessage()];
                    }
					return new JsonResponse(['elements' => $response]);
				}

				try {

					// validate submitted category id - replacing default method allows user privilege considerations

					$article
                        ->setCategory($this->validateArticleCategory(ArticleCategory::getInstance($v['articlecategoriesid'])))
					    ->setHeadline($v['headline'])
					    ->setData($v->all() /* content, teaser, subline */)
                        ->setCustomSort($v->get('customsort'))
                        ->setCustomFlags($v->get('customflags'))
                    ;

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
								'success' => true,
								'id' => $article->getId()
							]);
						}
						else {
							return new JsonResponse(['success' => true]);
						}
					}

					else {
						return new JsonResponse(['success' => true, 'message' =>'Keine Änderung, nichts gespeichert!']);
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
					MetaFile::getInstance(null, $this->request->request->getInt('file')),
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
     * @throws ArticleCategoryException
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws \vxPHP\Form\Exception\FormElementFactoryException
     * @throws \vxPHP\Form\Exception\HtmlFormException
     */
	private function buildEditForm() {

		$categories = ['-1' => 'Bitte Kategorie wählen...'];
		
		foreach(ArticleCategory::getArticleCategories('sortByCustomSort') as $c) {
			$categories[$c->getId()] = $c->getTitle();
		}

		return HtmlForm::create('admin_edit_article.htm')
			->setAttribute('class', 'editArticleForm')
			->addElement(FormElementFactory::create('select', 'articlecategoriesid', null, [], $categories, true, [], [new RegularExpression(Rex::INT_EXCL_NULL)], 'Es muss eine Artikelkategorie gewählt werden.'))
			->addElement(FormElementFactory::create('input', 'headline', null, [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Artikel benötigt eine Überschrift.'))
            ->addElement(FormElementFactory::create('input', 'subline', null, [], [], false, ['trim']))
			->addElement(FormElementFactory::create('textarea', 'teaser', null, [], [], false, ['trim', 'strip_tags']))
			->addElement(FormElementFactory::create('textarea', 'content', null, [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Artikel benötigt einen Inhalt.'))
			->addElement(FormElementFactory::create('input', 'article_date', null, [], [], false, ['trim'], [new Date(['locale' => new Locale('de')])], 'Ungültiges Datum'))
			->addElement(FormElementFactory::create('input', 'display_from', null, [], [], false, ['trim'], [new Date(['locale' => new Locale('de')])], 'Ungültiges Datum'))
			->addElement(FormElementFactory::create('input', 'display_until', null, [], [], false, ['trim'], [new Date(['locale' => new Locale('de')])], 'Ungültiges Datum'))
			->addElement(FormElementFactory::create('input', 'customsort', null, [], [], false, ['trim'], [new RegularExpression(Rex::EMPTY_OR_INT_EXCL_NULL)], 'Ungültiger Wert'))
            ->addElement(FormElementFactory::create('checkbox', 'customflags', 1))
        ;
	}

    /**
     * @param MetaFile $f
     * @return string
     * @throws \vxPHP\File\Exception\FilesystemFolderException
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
