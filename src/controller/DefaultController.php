<?php
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Template\Exception\SimpleTemplateException;
use vxPHP\Template\SimpleTemplate;

class DefaultController extends Controller {

	protected function execute() {

		// pick page from the end of the segments sequence

		if(count($this->pathSegments)) {
			$page = array_pop($this->pathSegments);
		}

		// alternatively fall back to the route id (for example on splash pages)

		else {
			$page = $this->route->getRouteId();
		}

		try {

			// load index template

			$include = new SimpleTemplate('default' . DIRECTORY_SEPARATOR . preg_replace('~[^a-z0-9_-]~i', '', $page) . '.php');

			if($include->containsPHP()) {
				$this->generateHttpError();
			}

			// replace content block with include for snippet in response

			return new Response(SimpleTemplate::create('layout.php')
				->insertTemplateAt($include, 'content_block')
				->display()
			);

		}

		catch(SimpleTemplateException $e) {
			$this->generateHttpError();
		}

	}
}