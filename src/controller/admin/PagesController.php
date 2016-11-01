<?php

namespace App\Controller\Admin;

use vxPHP\Template\SimpleTemplate;
use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Template\Filter\ShortenText;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;
use vxPHP\Application\Application;
use vxPHP\User\User;
use vxPHP\Routing\Router;
use vxPHP\Webpage\MenuGenerator;

use vxWeb\TemplateUtil;
use vxWeb\Orm\Page\Page;
use vxWeb\Orm\Page\PageException;
use vxPHP\Application\Locale\Locale;
use vxWeb\Orm\Page\Revision;

class PagesController extends Controller {

	private $maxPageRevisions = 5;

	protected function execute() {

		if(($id = $this->request->query->getInt('id'))) {

			MenuGenerator::setForceActiveMenu(TRUE);

			try {
				$page		= Page::getInstance($id);
				$revision	= $page->getActiveRevision();
				if(!$revision) {
					$revision = $page->getNewestRevision();
				}
			}
			catch (PageException $e) {
				return $this->redirect(Router::getRoute('pages', 'admin.php')->getUrl());
			}

			$form = HtmlForm::create('admin_edit_page.htm')
				->addElement(FormElementFactory::create('input',	'Title',		NULL, array('maxlength' => 128, 'class' => 'pct_100'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('input',	'Alias',		NULL, array('maxlength' => 64, 'class' => 'pct_100'), array(), TRUE, array('trim', 'uppercase')))
				->addElement(FormElementFactory::create('textarea', 'Keywords',		NULL, array('rows' => 4, 'cols' => '30', 'class' => 'pct_100'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('textarea', 'Description',	NULL, array('rows' => 4, 'cols' => '30', 'class' => 'pct_100'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('textarea', 'Markup',		NULL, array('rows' => 20, 'cols' => 40, 'class' => 'pct_100'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('button', 'submit_page', '', array('type' => 'submit'))->setInnerHTML('Änderungen übernehmen und neue Revision erzeugen'));
				
			return new Response(
				SimpleTemplate::create('admin/page_edit.php')
					->assign('form', $form->render())
					->assign('allow_nice_edit', !$revision->containsPHP())
					->display()
			);
		}

		TemplateUtil::syncTemplates();

		$pages = Page::getInstances();
		
		usort($pages, function(Page $a, Page $b) { return $a->getAlias() < $b->getAlias() ? -1 : 1; });
		
		return new Response(
			SimpleTemplate::create('admin/pages_list.php')
				->assign('pages', $pages)
				->addFilter(new ShortenText())
				->display()
		);
	}
	
	protected function xhrExecute() {

		try {
			
			if($revisionId = $this->request->request->getInt('revisionId')) {
				
				$form = HtmlForm::create()
					->addElement(FormElementFactory::create('input',	'Title',		NULL, array(), array(), FALSE, array('trim')))
					->addElement(FormElementFactory::create('textarea', 'Keywords',		NULL, array(), array(), FALSE, array('trim')))
					->addElement(FormElementFactory::create('textarea', 'Description',	NULL, array(), array(), FALSE, array('trim')))
					->addElement(FormElementFactory::create('textarea', 'Markup',		NULL, array(), array(), FALSE, array('trim')))
					->bindRequestParameters();

				if(!$form->getFormErrors()) {
					$v = $form->getValidFormValues();

					$revision = Revision::getInstance($revisionId);
					$revision
						->setTitle		($v['Title'])
						->setDescription($v['Description'])
						->setKeywords	($v['Keywords'])
						->setMarkup		($v['Markup']);
				
					if($revision->wasChanged()) {

						$revisionToAdd = clone $revision;
						$revisionToAdd
							->setActive(TRUE)
							->setAuthor(User::getSessionUser())
							->save();

						$revision->getPage()->exportActiveRevision();

						return new JsonResponse(array(
							'data'		=> array(
								'id'			=> $revision->getId(),
								'alias'			=> $revision->getPage()->getAlias(),
								'title'			=> $revision->getTitle(),
								'markup' 		=> $revision->getMarkup(),
								'description'	=> $revision->getDescription(),
								'keywords'		=> $revision->getKeywords()
							),
							'success'	=> TRUE,
							'message'	=> 'Aktualisierte Revision gespeichert und aktiviert.'
						));
					}
					
					return new JsonResponse(array('success' => FALSE, 'message' => 'Keine Änderungen erkannt, keine aktualisierte Revision gespeichert.'));

				}
				
				// handle possible form errors
			}
			
			$page = Page::getInstance($this->request->query->getInt('id'));

			$request = $this->request->request;
	
			switch($request->get('httpRequest')) {
	
				case 'getRevisions':
	
					$revisions = array();
	
					foreach($page->getRevisions() as $revision) {
						$revisions[] = array(
							'id'			=> $revision->getId(),
							'active'		=> $revision->isActive(),
							'locale'		=> (string) $revision->getLocale(),
							'firstCreated'	=> $revision->getFirstCreated()->format(DateTime::W3C)
						);
					}
					return new JsonResponse(array('revisions' => $revisions));
					
				case 'getRevisionData':
	
					$id			= $request->getInt('id');
					$revision	= Revision::getInstance($id);

					return new JsonResponse(array(
						'id'			=> $id,
						'alias'			=> $page->getAlias(),
						'title'			=> $revision->getTitle(),
						'markup' 		=> $revision->getMarkup(),
						'description'	=> $revision->getDescription(),
						'keywords'		=> $revision->getKeywords()
					));
					
				case 'changeActivationOfRevision':
					
					$revision = Revision::getInstance($request->getInt('id'));
					$activate = (boolean) $request->getInt('activate');

					if($activate === $revision->isActive()) {
						return new JsonResponse(array('success' => TRUE));
					}

					$revision->setActive($activate);

					if($activate) {
						$page->exportActiveRevision();
					}

					return new JsonResponse(array('success' => TRUE));
					
				case 'deleteRevision':

					$revision = Revision::getInstance($request->getInt('id'))->delete();
					return new JsonResponse(array('success' => TRUE));

			}
		}
			
		catch (PageException $e) {
			return new JsonResponse(array('success' => FALSE, 'message' => $e->getMessage()));
		}

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

}
