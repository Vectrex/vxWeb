<?php

namespace App\Controller\Admin;

use vxPHP\Template\SimpleTemplate;
use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Template\Filter\ShortenText;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;
use vxPHP\Routing\Router;
use vxPHP\Webpage\MenuGenerator;
use vxPHP\Application\Locale\Locale;
use vxPHP\Constraint\Validator\RegularExpression;
use vxPHP\Util\Rex;
use vxPHP\Application\Application;

use vxWeb\Util\Template;
use vxWeb\Model\Page\Page;
use vxWeb\Model\Page\PageException;
use vxWeb\Model\Page\Revision;

class PagesController extends Controller {

	private $maxPageRevisions = 5;

	protected function execute(): Response
    {
		Template::syncTemplates();

		return new Response(
			SimpleTemplate::create('admin/pages_list.php')->display()
		);
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

            try {
                $page = Page::getInstance($id);
                $revision = $page->getActiveRevision();
                if (!$revision) {
                    $revision = $page->getNewestRevision();
                }
            } catch (PageException $e) {
                return $this->redirect(Application::getInstance()->getRouter()->getRoute('pages')->getUrl());
            }

            $form = $this->buildEditForm();

            return new Response(
                SimpleTemplate::create('admin/page_edit.php')
                    ->assign('form', $form->render())
                    ->assign('allow_nice_edit', !$revision->containsPHP())
                    ->display()
            );
        }

        return new Response(null, Response::HTTP_NOT_FOUND);
    }

	protected function xhrExecute() {

		try {
			
			if($revisionId = $this->request->request->getInt('revisionId')) {
				
				$form = $this->buildEditForm();

				$form->bindRequestParameters();

				if(!$form->getFormErrors()) {
					$v = $form->getValidFormValues();

					$revision = Revision::getInstance($revisionId);
					$revision
						->setTitle		($v['title'])
						->setDescription($v['description'])
						->setKeywords	($v['keywords'])
						->setMarkup		($v['markup']);

					if($revision->wasChanged()) {

						$revision->deactivate();

						$revisionToAdd = clone $revision;
						$revisionToAdd
							->setActive(TRUE)
							->setAuthorId(Application::getInstance()->getCurrentUser()->getAttribute('id'))
							->save();

						$revisionToAdd->getPage()->exportActiveRevision();

						return new JsonResponse([
							'data'		=> [
								'id'			=> $revision->getId(),
								'alias'			=> $revision->getPage()->getAlias(),
								'title'			=> $revision->getTitle(),
								'markup' 		=> $revision->getMarkup(),
								'description'	=> $revision->getDescription(),
								'keywords'		=> $revision->getKeywords()
							],
							'success'	=> TRUE,
							'message'	=> 'Aktualisierte Revision gespeichert und aktiviert.'
						]);
					}
					
					return new JsonResponse([
						'success' => FALSE,
						'message' => 'Keine Änderungen erkannt, keine aktualisierte Revision gespeichert.'
					]);

				}
				
				// handle possible form errors
			}
			
			$page = Page::getInstance($this->request->query->getInt('id'));

			$request = $this->request->request;
	
			switch($request->get('httpRequest')) {
	
				case 'getRevisions':
	
					$revisions = [];
	
					foreach($page->getRevisions() as $revision) {
						$revisions[] = [
							'id'			=> $revision->getId(),
							'active'		=> $revision->isActive(),
							'locale'		=> (string) $revision->getLocale(),
							'firstCreated'	=> $revision->getFirstCreated()->format(\DateTime::W3C)
						];
					}
					return new JsonResponse(['revisions' => $revisions]);
					
				case 'getRevisionData':
	
					$id			= $request->getInt('id');
					$revision	= Revision::getInstance($id);

					return new JsonResponse([
						'id'			=> $id,
						'alias'			=> $page->getAlias(),
						'title'			=> $revision->getTitle(),
						'markup' 		=> $revision->getMarkup(),
						'description'	=> $revision->getDescription(),
						'keywords'		=> $revision->getKeywords()
					]);
					
				case 'changeActivationOfRevision':
					
					$revision = Revision::getInstance($request->getInt('id'));
					$activate = (boolean) $request->getInt('activate');

					if($activate === $revision->isActive()) {
						return new JsonResponse(['success' => TRUE]);
					}

					$revision->setActive($activate);

					if($activate) {
						$page->exportActiveRevision();
					}

					return new JsonResponse(['success' => TRUE]);
						
				case 'deleteRevision':

					$revision = Revision::getInstance($request->getInt('id'))->delete();
					return new JsonResponse(['success' => TRUE]);

			}
		}
			
		catch (PageException $e) {
			return new JsonResponse(['success' => FALSE, 'message' => $e->getMessage()]);
		}

	}

	protected function updateInlineEdit() {

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


	/**
	 * purge old revisions
	 * currently not used
	 * 
	 * @param Page $page
	 * @param Locale $locale
	 */
	private function purgeRevision(Page $page, Locale $locale = NULL) {

		if(count($page->getRevisions()) > $this->maxPageRevisions) {
			
			$page->getOldestRevision()->delete();
			
		}
	}

	private function buildEditForm() {
		
		return HtmlForm::create('admin_edit_page.htm')
			->addElement(FormElementFactory::create('input',	'title',		NULL, [], [], FALSE, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
			->addElement(FormElementFactory::create('input',	'alias',		NULL, [], [], FALSE, ['trim', 'uppercase']))
			->addElement(FormElementFactory::create('textarea', 'keywords',		NULL, [], [], FALSE, ['trim']))
			->addElement(FormElementFactory::create('textarea', 'description',	NULL, [], [], FALSE, ['trim']))
			->addElement(FormElementFactory::create('textarea', 'markup',		NULL, [], [], FALSE, ['trim']))
			->addElement(FormElementFactory::create('button', 'submit_page')->setInnerHTML('Änderungen übernehmen und neue Revision erzeugen'));

	}
}
