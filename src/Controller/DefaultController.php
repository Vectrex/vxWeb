<?php

namespace App\Controller;

use vxPHP\Application\Application;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\Exception\HttpException;
use vxPHP\Template\Exception\SimpleTemplateException;
use vxPHP\Template\Filter\AnchorHref;
use vxPHP\Template\Filter\AssetsPath;
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

			// superadmin sees inline editor of content block

            $currentUser = Application::getInstance()->getCurrentUser();

            if(!$currentUser || !$currentUser->hasRole('superadmin')) {

                // fall back to "layout.php" as parent template, when template has no "extend" directive

                if(is_null($include->getParentTemplateFilename())) {

                    // replace content block with vx for snippet in response

                    return new Response(SimpleTemplate::create('layout.php')
                        ->assign('route', $pageAlias ?? null)
                        ->assign('page', $page)
                        ->insertTemplateAt($include, 'content_block')
                        ->display()
                    );

                }

                else {

                    return new Response($include
                        ->assign('route', $pageAlias ?? null)
                        ->assign('page', $page)
                        ->display()
                    );

                }
            }

            else {

                return new Response(
                    $this->insertInlineEditor(
                        $include,
                        ['page' => $page, 'pageAlias' => $pageAlias]
                    )
                );

            }
		}

		catch(SimpleTemplateException $e) {
			throw new HttpException(Response::HTTP_NOT_FOUND); 
		}

	}

	private function insertInlineEditor(SimpleTemplate $include, array $parameters) {

	    $parentTemplate = SimpleTemplate::create($include->getParentTemplateFilename() ?? 'layout.php');

        $markup = $parentTemplate
            ->assign('route', $parameters['pageAlias'] ?? null)
            ->assign('page', $parameters['page'])
            ->display()
        ;

        // switch to DOMDocument to find parent node of comment reliably

        $doc = new \DOMDocument();
        $doc->loadHTML($markup);
        $xpath = new \DOMXPath($doc);

        foreach($xpath->query('//comment()') as $item) {

            if(preg_match('/\{\s*block:\s*content_block\s*\}/i', trim($item->data))) {

                $node = $item->parentNode;
                break;
            }

        }

        if($node) {

            $node->setAttribute('contenteditable', 'true');

        }

        // replace outer template contents

        $parentTemplate->setRawContents($doc->saveHTML());

        // add JS in header

        $parentTemplate->setRawContents(
            preg_replace(
                '/<\/head>/i',
                SimpleTemplate::create('admin/snippets/inline_editor.php')
                    ->assign('page', $parameters['page'])
                    ->display()
                . '</head>',
                $parentTemplate->getRawContents()
            )
        );

        return $parentTemplate
            ->insertTemplateAt($include, 'content_block')
            ->display([new AnchorHref(), new AssetsPath()])
        ;

    }
}
