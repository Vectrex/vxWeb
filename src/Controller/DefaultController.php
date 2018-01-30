<?php

namespace App\Controller;

use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\Exception\HttpException;
use vxPHP\Template\Exception\SimpleTemplateException;
use vxPHP\Template\SimpleTemplate;
use vxWeb\Model\Page\Page;
use vxWeb\Model\Page\PageException;

class DefaultController extends Controller {

	protected function execute() {

		try {
			
			// check for markup in parameters
			
			if(isset($this->parameters['markup'])) {

				$include = SimpleTemplate::create()->setRawContents($this->parameters['markup']);
				$page = NULL;
				
			}

			else {

					// pick page from the end of the segments sequence
		
				if(count($this->pathSegments)) {
					$pageAlias = array_pop($this->pathSegments);
				}
				// alternatively fall back to the route id (for example on splash pages)
		
				else {
                    $pageAlias = $this->route->getRouteId();
                }

                try {
                    $page = Page::getInstance($pageAlias);
                }
                catch (PageException $e) {
				    $page = null;
                }

				// load index template

				$include = new SimpleTemplate('default' . DIRECTORY_SEPARATOR . preg_replace('~[^a-z0-9_-]~i', '', $pageAlias) . '.php');
			}

			if($include->containsPHP()) {
				throw new HttpException(Response::HTTP_FORBIDDEN);
			}

			// fall back to "layout.php" as parent template, when template has no "extend" directive

			if(is_null($include->getParentTemplateFilename())) {
			
				// replace content block with vx for snippet in response

				return new Response(SimpleTemplate::create('layout.php')
					->assign('route', $pageAlias)
                    ->assign('page', $page)
					->insertTemplateAt($include, 'content_block')
					->display()
				);

			}

			else {

				return new Response($include
					->assign('route', $pageAlias)
                    ->assign('page', $page)
					->display()
				);
				
			}
		}

		catch(SimpleTemplateException $e) {
			throw new HttpException(Response::HTTP_NOT_FOUND); 
		}

	}
}