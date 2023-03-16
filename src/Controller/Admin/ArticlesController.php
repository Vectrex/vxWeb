<?php

namespace App\Controller\Admin;

use vxPHP\Http\ParameterBag;
use vxPHP\Util\Rex;

use vxPHP\Image\ImageModifierFactory;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Controller\Controller;

use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;

use vxWeb\Model\ArticleCategory\ArticleCategoryQuery;
use vxWeb\Model\MetaFile\Exception\MetaFileException;
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
use vxPHP\Constraint\Validator\Date;
use vxPHP\Application\Locale\Locale;
use vxPHP\Constraint\Validator\RegularExpression;

class ArticlesController extends Controller {

    use AdminControllerTrait;

    protected function execute(): Response
    {
        return new Response();
	}

	protected function list(): JsonResponse
    {
        $categories = [];
        $articles = [];

        foreach(ArticleCategoryQuery::create(Application::getInstance()->getVxPDO())->sortBy('customsort')->sortBy('title')->select() as $cat) {
            $categories[$cat->getId()] = [
                'id' => $cat->getId(),
                'alias' => $cat->getAlias(),
                'label' => $cat->getTitle()
            ];
        }

        foreach(ArticleQuery::create(Application::getInstance()->getVxPDO())->select() as $article) {
            $articles[] = [
                'id' => $article->getId(),
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

    protected function get(): JsonResponse
    {
        $db = Application::getInstance()->getVxPDO();

        $articleData = $db->doPreparedQuery("
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
                articlesid = ?", [$this->route->getPathParameter('id')])->current();

        return new JsonResponse($articleData);
    }

    protected function getCategories (): JsonResponse
    {
        $db = Application::getInstance()->getVxPDO();

        return new JsonResponse(
            (array) $db->doPreparedQuery("SELECT articlecategoriesid AS " . $db->quoteIdentifier('key') . ", title AS label FROM articlecategories ORDER BY title")
        );
    }

    protected function addOrUpdate (): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true));
        $admin = Application::getInstance()->getCurrentUser();

        if ($this->route->getRouteId() === 'article_update') {
            $id = $this->route->getPathParameter('id');
            try {
                $article = Article::getInstance($id);
            }
            catch(ArticleException $e) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }

            // check permission of non superadmin

            if(!$admin->hasRole('superadmin') && $admin->getAttribute('id') != $article->getCreatedById()) {
                return new JsonResponse(null, Response::HTTP_FORBIDDEN);
            }
        }
        else {
            $id = null;
            $article = new Article();
        }

        $form = $this->buildEditForm();
        $form
            ->disableCsrfToken()
            ->bindRequestParameters($bag)
            ->validate()
        ;
        if(!$form->getFormErrors()) {
            $v = $form->getValidFormValues();

            $article->setDate($v['article_date'] ? new \DateTime($v['article_date']) : null);
            $article->setDisplayFrom($v['display_from'] ? new \DateTime($v['display_from']) : null);
            $article->setDisplayUntil($v['display_until'] ? new \DateTime($v['display_until']) : null);

            $article
                ->setCategory($this->validateArticleCategory(ArticleCategory::getInstance($v['articlecategoriesid'])))
                ->setHeadline($v['headline'])
                ->setData($v->all() /* content, teaser, subline */)
                ->setCustomSort($v->get('customsort'))
                ->setCustomFlags($v->get('customflags'));

            if (!$id) {
                $article->setCreatedById($admin->getAttribute('id'));
            } else {
                $article->setUpdatedById($admin->getAttribute('id'));
            }

            if ($article->wasChanged()) {
                $article->save();
                if (!$id) {
                    return new JsonResponse([
                        'success' => true,
                        'id' => $article->getId(),
                        'message' => 'Artikel angelegt.'
                    ]);
                }
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Artikel aktualisiert.'
                ]);
            }
            return new JsonResponse([
                'success' => true,
                'message' => 'Keine Aktualisierung notwendig.'
            ]);
        }
        $response = [];

        foreach($form->getFormErrors() as $element => $error) {
            $response[$element] = $error->getErrorMessage();
        }
        return new JsonResponse(['success' => false, 'errors' => $response, 'message' => 'Formulardaten unvollständig oder fehlerhaft.']);
    }

	protected function publish (): JsonResponse
    {
		$id = $this->route->getPathParameter('id');
		$state = $this->route->getRouteId() === 'article_publish';
		$admin = Application::getInstance()->getCurrentUser();

        try {
            $article = Article::getInstance($id);
        }
        catch(ArticleException $e) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        if ($state) {

            // publish logs publishedById

            $article->publish($admin->getAttribute('id'))->save();
            $message = 'Artikel freigegeben.';
        }
        else {

            // unpublish sets publishedById to null

            $article->unpublish()->save();
            $message = 'Freigabe des Artikels zurückgezogen.';
        }

        return new JsonResponse(['success' => true, 'message' => $message]);
	}

	protected function del (): JsonResponse
    {
        $id = $this->route->getPathParameter('id');

        try {
            $article = Article::getInstance($id);
        }
        catch(ArticleException $e) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        // check permission of non superadmin

        $admin = Application::getInstance()->getCurrentUser();

        if(!$admin->hasRole('superadmin') && $admin->getAttribute('id') != $article->getCreatedById()) {
            return new JsonResponse(null, Response::HTTP_FORBIDDEN);
        }

        try {
            $article->delete();
        }
        catch(ArticleException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['success' => true, 'message' => 'Artikel erfolgreich gelöscht.']);
    }

    protected function add (): Response
    {
        MenuGenerator::setForceActiveMenu(true);

        $uploadMaxFilesize = min(
            $this->toBytes(ini_get('upload_max_filesize')),
            $this->toBytes(ini_get('post_max_size'))
        );
        $maxExecutionTime = ini_get('max_execution_time');

        return new Response(
            SimpleTemplate::create('admin/articles_edit.php')
                ->assign('upload_max_filesize', $uploadMaxFilesize)
                ->assign('max_execution_time_ms', $maxExecutionTime * 900)// 10pct "safety margin"
                ->display()
        );
    }

    protected function fileLink (): JsonResponse
    {
        try {
            $article = Article::getInstance($this->route->getPathParameter('id'));
            $file = MetaFile::getInstance(null, $this->request->query->getInt('fileId'));
        }
        catch(\Exception $e) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }
        if (in_array($file, $article->getLinkedMetaFiles())) {
            $article->unlinkMetaFile($file)->save();
            return new JsonResponse(['success' => true, 'status' => 'unlinked']);
        }
        $article->linkMetaFile($file)->save();
        return new JsonResponse(['success' => true, 'status' => 'linked']);
    }

    protected function getLinkedFiles (): JsonResponse
    {
        try {
            $article = Article::getInstance($this->route->getPathParameter('id'));
        }
        catch (MetaFileException $e) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $rows = [];

        $visibleFiles = $article->getLinkedMetaFiles();
        $host = $this->request->getSchemeAndHttpHost();

        foreach($article->getLinkedMetaFiles(true) as $mf) {
            $row = [
                'id' => $mf->getId(),
                'folderid' => $mf->getMetaFolder()->getId(),
                'filename' => $mf->getFilename(),
                'isThumb' => $mf->isWebImage(),
                'type' => $mf->getMimetype(),
                'path' => $mf->getMetaFolder()->getRelativePath(),
                'hidden' => !in_array($mf, $visibleFiles)
            ];
            if ($mf->isWebImage()) {
                $row['src'] = $host . $this->getThumbPath($mf);
            }

            $rows[] = $row;
        }

        return new JsonResponse($rows);
    }

    protected function updateLinkedFiles (): JsonResponse
    {
        try {
            $article = Article::getInstance($this->route->getPathParameter('id'));
        }
        catch (MetaFileException $e) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $fileIds = (new ParameterBag(json_decode($this->request->getContent(), true)))->get('fileIds', []);

        foreach ($article->getLinkedMetaFiles(true) as $linkedMf) {
            if (!in_array($linkedMf->getId(), $fileIds, true)) {
                $article->unlinkMetaFile($linkedMf);
            }
        }

        foreach(MetaFile::getInstancesByIds($fileIds) as $ndx => $mf) {
            $article->setCustomSortOfMetaFile($mf, $ndx);
        }

        $article->save();

        return new JsonResponse(['success' => true]);
    }

    protected function toggleLinkedFile (): JsonResponse
    {
        try {
            $article = Article::getInstance($this->route->getPathParameter('id'));
        }
        catch (MetaFileException $e) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        $bag = new ParameterBag(json_decode($this->request->getContent(), true));
        $fileId = $bag->get('fileId');

        $linkedFiles = $article->getLinkedMetaFiles(true);
        foreach ($article->getLinkedMetaFiles(true) as $file) {
            if ($file->getId() === $fileId) {
                $newState = !$article->getLinkedFileVisibility($file);
                $article->setLinkedFileVisibility($file, $newState);
                $article->save();
                return new JsonResponse (['success' => true, 'hidden' => !$newState]);
            }
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

	/**
	 * @param ArticleCategory $cat
	 * @return ArticleCategory
	 */
	private function validateArticleCategory(ArticleCategory $cat): ArticleCategory
    {
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
	private function buildEditForm(): HtmlForm
    {
		return HtmlForm::create()
			->addElement(FormElementFactory::create('select', 'articlecategoriesid', null, [], [], true, [], [new RegularExpression(Rex::INT_EXCL_NULL)], 'Es muss eine Artikelkategorie gewählt werden.'))
			->addElement(FormElementFactory::create('input', 'headline', null, [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Artikel benötigt eine Überschrift.'))
            ->addElement(FormElementFactory::create('input', 'subline', null, [], [], false, ['trim']))
			->addElement(FormElementFactory::create('textarea', 'teaser', null, [], [], false, ['trim', 'strip_tags']))
			->addElement(FormElementFactory::create('textarea', 'content', null, [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)], 'Der Artikel benötigt einen Inhalt.'))
			->addElement(FormElementFactory::create('input', 'article_date', null, [], [], false, ['trim'], [new Date(['locale' => new Locale('iso')])], 'Ungültiges Datum'))
			->addElement(FormElementFactory::create('input', 'display_from', null, [], [], false, ['trim'], [new Date(['locale' => new Locale('iso')])], 'Ungültiges Datum'))
			->addElement(FormElementFactory::create('input', 'display_until', null, [], [], false, ['trim'], [new Date(['locale' => new Locale('iso')])], 'Ungültiges Datum'))
			->addElement(FormElementFactory::create('input', 'customsort', null, [], [], false, ['trim'], [new RegularExpression(Rex::EMPTY_OR_INT_EXCL_NULL)], 'Ungültiger Wert'))
            ->addElement(FormElementFactory::create('checkbox', 'customflags', 1))
        ;
	}

    /**
     * @param MetaFile $f
     * @return string
     * @throws \vxPHP\File\Exception\FilesystemFolderException
     */
	private function getThumbPath(MetaFile $f): string
    {
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
