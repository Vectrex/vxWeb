<?php

namespace App\Controller;

use vxPHP\Application\Application;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\Exception\HttpException;
use vxPHP\Template\Exception\SimpleTemplateException;
use vxPHP\Template\Filter\AnchorHref;
use vxPHP\Template\Filter\AssetsPath;
use vxPHP\Template\Filter\ImageCache;
use vxPHP\Template\SimpleTemplate;
use vxWeb\Model\Page\Page;
use vxWeb\Model\Page\PageException;
use IvoPetkov\HTML5DOMDocument;

class DefaultController extends Controller
{
	protected function execute(): Response
    {
        if(stripos($this->getRequest()->headers->get('accept'), 'text/html') !== 0) {
            throw new HttpException(Response::HTTP_NOT_FOUND);
        }

		try {
			
			// check for markup in parameters
			
			if(isset($this->parameters['markup'])) {
				$include = SimpleTemplate::create()->setRawContents($this->parameters['markup']);
				$page = null;
			}
			else {
			    $app = Application::getInstance();

			    // pick page from the end of the segments sequence

                $pathSegments = explode('/', trim($this->request->getPathInfo(), '/'));

                // skip script name

                if($app->getRouter()->getServerSideRewrite() && 'index.php' !== basename($this->request->getScriptName())) {
                    array_shift($pathSegments);
                }

                // skip locale if one found

                if(count($pathSegments) && $app->hasLocale($pathSegments[0])) {
                    array_shift($pathSegments);
                }

                if(count($pathSegments) && $pathSegments[0]) {
					$pageAlias = array_pop($pathSegments);
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

                return new Response($include
                    ->assign('route', $pageAlias ?? null)
                    ->assign('page', $page)
                    ->display()
                );
            }

            return new Response(
                $this->insertInlineEditor(
                    $include,
                    ['page' => $page, 'pageAlias' => $pageAlias ?? null]
                )
            );
		}

		catch(SimpleTemplateException $e) {
			throw new HttpException(Response::HTTP_NOT_FOUND); 
		}
	}

	private function insertInlineEditor(SimpleTemplate $include, array $parameters): string
    {
	    $parentTemplate = SimpleTemplate::create($include->getParentTemplateFilename() ?? 'layout.php');

        // remove a possible extend-comment to avoid cascaded inclusion

        $include->setRawContents(preg_replace('~<!--\s*\{\s*extend:\s*([\w./-]+)\s*@\s*([\w-]+)\s*\}\s*-->~', '', $include->getRawContents()));

        $markup = $parentTemplate
            ->assign('route', $parameters['pageAlias'] ?? null)
            ->assign('page', $parameters['page'])
            ->display()
        ;

        // switch to DOMDocument to find parent node of comment reliably

        $doc = new HTML5DOMDocument();
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
                . '</head>' . '<div id="messageBox" class="toast"><button class="btn btn-clear float-right"></button></div>',
                $parentTemplate->getRawContents()
            )
        );

        return $parentTemplate
            ->insertTemplateAt($include, 'content_block')
            ->display([new AnchorHref(), new AssetsPath(), new ImageCache()])
        ;
    }
}
