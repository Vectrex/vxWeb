<?php

namespace App\Controller\Admin;

use DateTimeInterface;
use vxPHP\Application\Application;
use vxPHP\Application\Exception\ApplicationException;
use vxPHP\Application\Exception\ConfigException;
use vxPHP\Constraint\Validator\RegularExpression;
use vxPHP\Controller\Controller;
use vxPHP\Form\Exception\FormElementFactoryException;
use vxPHP\Form\Exception\HtmlFormException;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\HtmlForm;
use vxPHP\Http\JsonResponse;
use vxPHP\Http\ParameterBag;
use vxPHP\Http\Response;
use vxPHP\Security\Csrf\Exception\CsrfTokenException;
use vxPHP\Util\Rex;
use vxWeb\Model\Page\Page;
use vxWeb\Model\Page\PageException;
use vxWeb\Model\Page\Revision;
use vxWeb\Util\Template;

class PagesController extends Controller
{
    /**
     * @return JsonResponse
     * @throws \Throwable
     * @throws ApplicationException
     */
    protected function list(): JsonResponse
    {
        Template::syncTemplates();
        $pages = Page::getInstances() ?? [];

        $rows = [];

        foreach ($pages as $page) {
            $rows[] = [
                'id' => $page->getId(),
                'alias' => $page->getAlias(),
                'template' => $page->getTemplate(),
                'updated' => $page->getOldestRevision()->getLastUpdated()->format('Y-m-d H:i:s'),
                'revisionCount' => count($page->getRevisions())
            ];
        }

        return new JsonResponse($rows);
    }

    /**
     * @return JsonResponse
     * @throws ApplicationException
     * @throws \Throwable
     */
    protected function get(): JsonResponse
    {
        try {
            $page = Page::getInstance($this->route->getPathParameter('id'));
            $revision = $page->getActiveRevision();
            if (!$revision) {
                $revision = $page->getNewestRevision();
            }
        } catch (PageException) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'current' => $this->getPageData($revision),
            'revisions' => $this->getRevisions($page)
        ]);
    }

    /**
     * @return JsonResponse
     * @throws ApplicationException
     * @throws \Throwable
     */
    protected function del(): JsonResponse
    {
        try {
            Page::getInstance($this->route->getPathParameter('id'))->delete();
            return new JsonResponse(['success' => true, 'message' => 'Seite erfolgreich gelöscht.']);
        } catch (PageException $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @return JsonResponse
     * @throws ApplicationException
     * @throws PageException
     * @throws \JsonException
     * @throws \Throwable
     * @throws ConfigException
     * @throws HtmlFormException
     * @throws CsrfTokenException
     */
    protected function addOrUpdate(): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        $form = $this->buildEditForm();
        $id = $this->route->getPathParameter('id');

        $v = $form
            ->disableCsrfToken()
            ->bindRequestParameters($bag)
            ->validate()
            ->getValidFormValues();

        if ($id) {
            try {
                $page = Page::getInstance($this->route->getPathParameter('id'));
            } catch (PageException) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }
        }

        $errors = $form->getFormErrors();

        // add a page

        if (!$id && !isset($errors['alias'])) {

            // check for valid alias

            if (!$v['alias']) {
                $form->setError('alias', null, 'Ein eindeutiger Seitenname ist erforderlich.');
            } else {
                try {
                    Page::getInstance($v['alias']);
                    $form->setError('alias', null, 'Ein Seite mit diesem Seitennamen existiert bereits.');
                } catch (PageException) {
                }
            }

            // set and export inital revision

            if (!$errors = $form->getFormErrors()) {
                Application::getInstance()->getVxPDO()->execute('INSERT INTO pages (alias, template) VALUES (?, ?)', [$v['alias'], strtolower($v['alias']) . '.php']);
                $page = Page::getInstance($v['alias']);
                $revisionToAdd = new Revision($page);
                $revisionToAdd
                    ->setTitle($v['title'])
                    ->setDescription($v['description'])
                    ->setKeywords($v['keywords'])
                    ->setMarkup($v['markup'])
                    ->setActive(true)
                    ->setAuthorId(Application::getInstance()->getCurrentUser()->getAttribute('id'))
                    ->save();
                $revisionToAdd->getPage()->exportActiveRevision();

                return new JsonResponse([
                    'success' => true,
                    'id' => $page->getId(),
                    'revisions' => $this->getRevisions($page),
                    'message' => 'Neue Seite angelegt und Revisionierung aktiviert.'
                ]);
            }
        }

        // create new revision for existing page

        if (!$errors) {
            $revision = $page->getActiveRevision() ?: $page->getNewestRevision();

            $revision
                ->setTitle($v['title'])
                ->setDescription($v['description'])
                ->setKeywords($v['keywords'])
                ->setMarkup($v['markup']);

            if ($revision->wasChanged()) {

                $revision->deactivate();

                $revisionToAdd = clone $revision;
                $revisionToAdd
                    ->setActive(true)
                    ->setAuthorId(Application::getInstance()->getCurrentUser()->getAttribute('id'))
                    ->save();
                $revisionToAdd->getPage()->exportActiveRevision();

                return new JsonResponse(['success' => true, 'revisions' => $this->getRevisions($page), 'message' => 'Aktualisierte Revision gespeichert und aktiviert.']);
            }
            return new JsonResponse(['success' => true, 'revisions' => $this->getRevisions($page), 'message' => 'Keine Änderungen erkannt, keine aktualisierte Revision gespeichert.']);
        }

        return new JsonResponse([
            'success' => false,
            'errors' => array_map(fn($error) => $error->getMessage(), $form->getFormErrors()),
            'message' => 'Formulardaten unvollständig oder fehlerhaft.'
        ]);
    }

    /**
     * @return JsonResponse
     * @throws \Throwable
     */
    protected function loadRevision(): JsonResponse
    {
        try {
            $revision = Revision::getInstance($this->route->getPathParameter('id'));
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['success' => true, 'current' => $this->getPageData($revision)]);
    }

    /**
     * @return JsonResponse
     * @throws ApplicationException
     * @throws PageException
     * @throws \Throwable
     */
    protected function activateRevision(): JsonResponse
    {
        try {
            $revision = Revision::getInstance($this->route->getPathParameter('id'));
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        if ($revision->isActive()) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Revision bereits aktiv.'
            ]);
        }

        $revision->setActive(true);
        $revision->getPage()->exportActiveRevision();

        return new JsonResponse([
            'success' => true,
            'message' => 'Revision aktiviert.',
            'current' => $this->getPageData($revision)
        ]);
    }

    /**
     * @return JsonResponse
     * @throws \Throwable
     */
    protected function delRevision(): JsonResponse
    {
        try {
            $revision = Revision::getInstance($this->route->getPathParameter('id'));
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        if ($revision->isActive()) {
            return new JsonResponse(['success' => false, 'message' => 'Revision ist noch aktiv.'], Response::HTTP_BAD_REQUEST);
        }
        $revision->delete();
        return new JsonResponse([
            'success' => true,
            'message' => 'Revision erfolgreich gelöscht.',
            'revisions' => $this->getRevisions($revision->getPage())
        ]);
    }

    /**
     * @param Revision $revision
     * @return array
     */
    private function getPageData(Revision $revision): array
    {
        return [
            'alias' => $revision->getPage()->getAlias(),
            'title' => $revision->getTitle(),
            'markup' => $revision->getMarkup(),
            'description' => $revision->getDescription(),
            'keywords' => $revision->getKeywords(),
            'revisionId' => $revision->getId()
        ];
    }

    /**
     * @param Page $page
     * @return array
     * @throws ApplicationException
     */
    private function getRevisions(Page $page): array
    {
        $revisions = [];

        foreach ($page->getRevisions() as $revision) {
            $revisions[] = [
                'id' => $revision->getId(),
                'authorId' => $revision->getAuthorId(),
                'active' => $revision->isActive(),
                'locale' => (string)$revision->getLocale(),
                'firstCreated' => $revision->getFirstCreated()->format(DateTimeInterface::W3C)
            ];
        }

        return $revisions;
    }

    /**
     * @return HtmlForm
     * @throws HtmlFormException
     * @throws FormElementFactoryException
     */
    private function buildEditForm(): HtmlForm
    {
        return HtmlForm::create()
            ->addElement(FormElementFactory::create('input', 'title', null, [], [], false, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
            ->addElement(FormElementFactory::create('input', 'alias', null, [], [], false, ['trim', 'uppercase'], [new RegularExpression('/^[a-z][a-z0-9_-]+$/i')], 'Der Name muss mit einem Buchstaben beginnen und darf nur die Zeichen A-Z, 0-9, sowie "_" und "-" enthalten.'))
            ->addElement(FormElementFactory::create('textarea', 'keywords', null, [], [], false, ['trim']))
            ->addElement(FormElementFactory::create('textarea', 'description', null, [], [], false, ['trim']))
            ->addElement(FormElementFactory::create('textarea', 'markup', null, [], [], false, ['trim']));
    }
}
