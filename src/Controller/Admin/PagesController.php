<?php

namespace App\Controller\Admin;

use vxPHP\Http\ParameterBag;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;
use vxPHP\Webpage\MenuGenerator;
use vxPHP\Constraint\Validator\RegularExpression;
use vxPHP\Util\Rex;
use vxPHP\Application\Application;

use vxWeb\Util\Template;
use vxWeb\Model\Page\Page;
use vxWeb\Model\Page\PageException;
use vxWeb\Model\Page\Revision;

class PagesController extends Controller
{
	protected function execute(): Response
    {
		Template::syncTemplates();

		return new Response(SimpleTemplate::create('admin/pages_list.php')->display());
	}

	protected function init (): JsonResponse
    {
        $pages = Page::getInstances() ?? [];

        $rows = [];

        foreach($pages as $page) {
            $rows[] = [
                'key' => $page->getId(),
                'alias' => $page->getAlias(),
                'template' => $page->getTemplate(),
                'updated' => $page->getOldestRevision()->getLastUpdated()->format('Y-m-d H:i:s'),
                'revisionCount' => count($page->getRevisions())
            ];
        }

        return new JsonResponse(['pages' => $rows]);
    }

    protected function edit (): Response
    {
        if(($id = $this->request->query->getInt('id'))) {

            MenuGenerator::setForceActiveMenu(true);

            return new Response(
                SimpleTemplate::create('admin/page_edit.php')
                    ->assign('id', $id)
                    ->display()
            );
        }

        return new Response(null, Response::HTTP_NOT_FOUND);
    }

    protected function editInit (): JsonResponse
    {
        if(!($id = $this->request->query->getInt('id'))) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        try {
            $page = Page::getInstance($id);
            $revision = $page->getActiveRevision();
            if (!$revision) {
                $revision = $page->getNewestRevision();
            }
        } catch (PageException $e) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'form' => $this->getPageData($revision),
            'revisions' => $this->getRevisions($page)
        ]);
    }

    protected function del (): JsonResponse
    {
        if(!($id = $this->request->query->getInt('id'))) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        try {
            Page::getInstance($id)->delete();
            return new JsonResponse(['success' => 1]);
        } catch (PageException $e) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }

    protected function update (): JsonResponse
    {
        try {
            $page = Page::getInstance($this->request->query->getInt('id'));
            $revision = $page->getActiveRevision();
            if (!$revision) {
                $revision = $page->getNewestRevision();
            }
        }
        catch (PageException $e) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $bag = new ParameterBag(json_decode($this->request->getContent(), true));

        $form = $this->buildEditForm();

        $v = $form
            ->disableCsrfToken()
            ->bindRequestParameters($bag)
            ->validate()
            ->getValidFormValues()
        ;
        if(!($errors = $form->getFormErrors())) {

            $revision
                ->setTitle($v['title'])
                ->setDescription($v['description'])
                ->setKeywords($v['keywords'])
                ->setMarkup($v['markup'])
            ;

            if($revision->wasChanged()) {

                $revision->deactivate();

                $revisionToAdd = clone $revision;
                $revisionToAdd
                    ->setActive(true)
                    ->setAuthorId(Application::getInstance()->getCurrentUser()->getAttribute('id'))
                    ->save()
                ;
                $revisionToAdd->getPage()->exportActiveRevision();

                return new JsonResponse(['success' => true, 'revisions' => $this->getRevisions($page), 'message' => 'Aktualisierte Revision gespeichert und aktiviert.']);
            }
            return new JsonResponse(['success' => true, 'revisions' => $this->getRevisions($page), 'message' => 'Keine Änderungen erkannt, keine aktualisierte Revision gespeichert.']);
        }

        $err = [];

        foreach($errors as $element => $error) {
            $err[$element] = $error->getErrorMessage();
        }

        return new JsonResponse(['success' => false, 'errors' => $err, 'message' => 'Formulardaten unvollständig oder fehlerhaft.']);
    }

    protected function loadRevision (): JsonResponse
    {
        try {
            $revision = Revision::getInstance($this->request->query->getInt('id'));
        }
        catch(\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['success' => true, 'form' => $this->getPageData($revision)]);
    }

    protected function activateRevision (): JsonResponse
    {
        try {
            $revision = Revision::getInstance($this->request->query->getInt('id'));
        }
        catch(\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        if($revision->isActive()) {
            return new JsonResponse(['success' => true]);
        }

        $revision->setActive(true);
        $revision->getPage()->exportActiveRevision();

        return new JsonResponse([
            'success' => true,
            'form' => $this->getPageData($revision),
            'revisions' => $this->getRevisions($revision->getPage())
        ]);
    }

    protected function deleteRevision (): JsonResponse
    {
        try {
            $revision = Revision::getInstance($this->request->query->getInt('id'));
            $page = $revision->getPage();
        }
        catch(\Exception $e) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        if($revision->isActive()) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }
        $revision->delete();
        return new JsonResponse(['success' => true, 'revisions' => $this->getRevisions($page)]);
    }

    protected function inlineEdit()
    {
        try {
            $post = json_decode($this->request->getContent(), true);
            $revision = Page::getInstance($post['page'])->getActiveRevision();
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        }

        $revision->setMarkup($post['data']);

        if($revision->wasChanged()) {

            $revision->deactivate();

            $revisionToAdd = clone $revision;
            $revisionToAdd
                ->setActive(true)
                ->setAuthorId(Application::getInstance()->getCurrentUser()->getAttribute('id'))
                ->save()
            ;

            $revisionToAdd->getPage()->exportActiveRevision();

            return new JsonResponse(['success' => true, 'message' => 'Aktualisierte Revision gespeichert und aktiviert.']);
        }

        return new JsonResponse(['success' => true, 'message' => 'Keine Änderungen erkannt, keine aktualisierte Revision gespeichert.']);
    }

    private function getRevisions (Page $page): array
    {
        $revisions = [];

        foreach($page->getRevisions() as $revision) {
            $revisions[] = [
                'id' => $revision->getId(),
                'active' => $revision->isActive(),
                'locale' => (string) $revision->getLocale(),
                'firstCreated' => $revision->getFirstCreated()->format(\DateTime::W3C)
            ];
        }

        return $revisions;
    }

    private function getPageData (Revision $revision): array
    {
        return [
            'alias' => $revision->getPage()->getAlias(),
            'title' => $revision->getTitle(),
            'markup' => $revision->getMarkup(),
            'description' => $revision->getDescription(),
            'keywords' => $revision->getKeywords()
        ];
    }

	private function buildEditForm() {
		return HtmlForm::create()
			->addElement(FormElementFactory::create('input', 'title', null, [], [], false, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
			->addElement(FormElementFactory::create('input', 'alias', null, [], [], false, ['trim', 'uppercase']))
			->addElement(FormElementFactory::create('textarea', 'keywords', null, [], [], false, ['trim']))
			->addElement(FormElementFactory::create('textarea', 'description', null, [], [], false, ['trim']))
			->addElement(FormElementFactory::create('textarea', 'markup', null, [], [], false, ['trim']))
        ;
	}
}
