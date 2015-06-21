<?php
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

use vxWeb\TemplateUtil;
use vxWeb\Orm\Page\Page;
use vxWeb\Orm\Page\PageException;
use vxPHP\Application\Locale\Locale;
use vxWeb\Orm\Page\Revision;

/**
 * @todo sanitize markup with HTMLPurifier
 */

class PagesController extends Controller {

	private $maxPageRevisions = 5;

	protected function execute() {

		if(($id = $this->request->query->getInt('id'))) {

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

			$form = HtmlForm::create('admin_page_edit.htm')
				->initVar('success', (int) !is_null($this->request->query->get('success')))
				->initVar('nochange', (int) !is_null($this->request->query->get('nochange')))
				->addElement(FormElementFactory::create('input',	'Title',		$revision	->getTitle($page->getTitle()), array('maxlength' => 128, 'class' => 'pct_100'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('input',	'Alias',		$page		->getAlias(), array('maxlength' => 64, 'class' => 'pct_100'), array(), TRUE, array('trim', 'uppercase')))
				->addElement(FormElementFactory::create('textarea', 'Keywords',		$revision	->getKeywords($page->getKeywords()), array('rows' => 4, 'cols' => '30', 'class' => 'pct_100'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('textarea', 'Description',	$revision	->getDescription(), array('rows' => 4, 'cols' => '30', 'class' => 'pct_100'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('textarea', 'Markup',		htmlspecialchars($revision->getMarkup(), ENT_NOQUOTES, 'UTF-8'), array('rows' => 20, 'cols' => 40, 'class' => 'pct_100'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('button', 'submit_edit', '', array('type' => 'submit'))->setInnerHTML('Änderungen übernehmen und neue Revision erzeugen'));
				
			if($form->bindRequestParameters()->wasSubmittedByName('submit_edit')) {

				$v = $form->getValidFormValues();

				$revision
					->setTitle		($v['Title'])
					->setDescription($v['Description'])
					->setKeywords	($v['Keywords'])
					->setMarkup		($v['Markup']);
				
				if($revision->wasChanged()) {
					try {
						$revisionToAdd = clone $revision;
						$revisionToAdd
							->setActive(TRUE)
							->setAuthor(User::getSessionUser())
							->save();
						$page->exportActiveRevision();

						// remove oldest revision, when maxPageRevisions is exceeded

						//$this->purgeRevision($page);

						return $this->redirect(Router::getRoute('pages', 'admin.php')->getUrl(), array('id' => $page->getId(), 'success' => 'true'));
					}
					catch(PageException $e) {
						$form->setError('system');
					}
				}
				
				else {
					return $this->redirect(Router::getRoute('pages', 'admin.php')->getUrl(), array('id' => $id, 'nochange' => 'true'));
				}
				
			}

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
			}
		}
			
		catch (PageException $e) {
			return new JsonResponse(array('success' => FALSE, 'message' => $e->getMessage()));
		}

	}

	private function purgeRevision(Page $page, Locale $locale = NULL) {

		if(count($page->getRevisions()) > $this->maxPageRevisions) {
			
			$page->getOldestRevision()->delete();
			
		}
	}

}
