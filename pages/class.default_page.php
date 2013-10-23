<?php

use vxPHP\Template\Exception\SimpleTemplateException;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Template\Filter\TextToLinks;

class default_page extends page {

	public function __construct() {

		parent::__construct();

		$pathSegments = $this->pathSegments;

		// pick page from the end of the segments sequence

		$page = array_pop($pathSegments);

		// alternatively fall back to the route id (for example on splash pages)

		if(empty($page)) {
			$page = $this->route->getRouteId();
		}

		try {
			$this->contentTpl = new SimpleTemplate(preg_replace('~[^a-z0-9_-]~i', '', $page) . '.htm');
		}
		catch(SimpleTemplateException $e) {
			self::generateHttpError();
		}

		if($this->contentTpl->containsPHP()) {
			self::generateHttpError();
		}
	}

	public function content() {
		$this->contentTpl->addFilter(new TextToLinks());

		$html = $this->contentTpl->display();
		$this->html .= $html;
		return $html;
	}
}
