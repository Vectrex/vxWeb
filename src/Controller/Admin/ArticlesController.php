<?php

namespace App\Controller\Admin;

use vxPHP\Http\ParameterBag;
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
use vxPHP\Webpage\MenuGenerator;
use vxPHP\Database\Util;
use vxPHP\Constraint\Validator\Date;
use vxPHP\Application\Locale\Locale;
use vxPHP\Constraint\Validator\RegularExpression;
use vxWeb\Model\ArticleCategory\ArticleCategoryQuery;

class ArticlesController extends Controller {

	protected function execute()
    {
		return new Response(SimpleTemplate::create('admin/articles_list.php')->display());
	}

	protected function init()
    {
        $categories = [];
        $articles = [];

        foreach(ArticleCategoryQuery::create(Application::getInstance()->getDb())->sortBy('customsort')->sortBy('title')->select() as $cat) {
            $categories[$cat->getId()] = [
                'id' => $cat->getId(),
                'alias' => $cat->getAlias(),
                'label' => $cat->getTitle()
            ];
        }

        foreach(ArticleQuery::create(Application::getInstance()->getDb())->select() as $article) {
            $articles[] = [
                'key' => $article->getId(),
                'title' => $article->getHeadline(),
                'catId' => $article->getCategory()->getId(),
                'pub' => $article->isPublished(),
                'marked' => $article->getCustomFlags(),
                'date' => $article->getDate() ? $article->getDate()->format('Y-m-d') : null,
                'from' => $article->getDisplayFrom() ? $article->getDisplayFrom()->format('Y-m-d') : null,
                'until' => $article->getDisplayUntil() ? $article->getDisplayUntil()->format('Y-m-d') : null,
                'sort' => $article->getCustomSort(),
                'updated' => $article->getLastUpdated() ? $article->getLastUpdated()->format('Y-m-d H:i:s') : null
            ];
        }

        return new JsonResponse([
            'categories' => array_values($categories),
            'articles' => $articles
        ]);
    }

    protected function editInit()
    {
        $db = Application::getInstance()->getVxPDO();

        if ($id = $this->request->query->getInt('id')) {
            $formData = $db->doPreparedQuery("
                SELECT
                    articlesid as id,
                    articlecategoriesid,
                    article_date,
                    display_from,
                    display_until,
                    headline,
                    subline,
                    teaser,
                    content,
                    customsort,
                    customflags,
                    published
                FROM
                    articles
                WHERE
                    articlesid = ?", [$id])->current();
        }

        return new JsonResponse([
            'form' => $formData ?? null,
            'options' => [
                'categories' => (array) $db->doPreparedQuery("SELECT articlecategoriesid AS id, title FROM articlecategories ORDER BY title")
            ]
        ]);
    }

	protected function publish()
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true));
		$id = $bag->getInt('id');
		$state = $bag->getInt('state');
		$admin = Application::getInstance()->getCurrentUser();

        if(!$id) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        try {
            $article = Article::getInstance($id);
        }
        catch(ArticleException $e) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
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

	protected function del ()
    {
        $id = $this->request->query->get('id');

        if(!$id) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        try {
            $article = Article::getInstance($id);
        }
        catch(ArticleException $e) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        // check permission of non superadmin

        $admin = Application::getInstance()->getCurrentUser();

        if(!$admin->hasRole('superadmin') && $admin->getAttribute('id') != $article->getCreatedById()) {
            return new Response('', Response::HTTP_FORBIDDEN);
        }

        try {
            $article->delete();
        }
        catch(ArticleException $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['success' => true]);
    }

    protected function add ()
    {
        MenuGenerator::setForceActiveMenu(true);

        return new Response(
            SimpleTemplate::create('admin/articles_edit.php')->display()
        );
    }

    protected function edit ()
    {
        if(!($id = $this->request->query->getInt('id'))) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        try {
            $article = Article::getInstance($id);
        }
        catch(ArticleException $e) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        // check permission of non superadmin

        $admin = Application::getInstance()->getCurrentUser();

        if(!$admin->hasRole('superadmin') && $admin->getAttribute('id') != $article->getCreatedById()) {
            return new Response('', Response::HTTP_FORBIDDEN);
        }

        MenuGenerator::setForceActiveMenu(true);

        return new Response(
            SimpleTemplate::create('admin/articles_edit.php')
                ->assign('article', $article)
                ->display()
        );
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
	private function buildEditForm()
    {
		return HtmlForm::create()
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
