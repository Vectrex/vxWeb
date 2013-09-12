<?php

use vxPHP\Template\Exception\SimpleTemplateException;
use vxPHP\Template\SimpleTemplate;

class default_page extends page {

	public function __construct() {

		parent::__construct();

		$pathSegments = $this->pathSegments;

		// pick page from the end of the segments sequence

		$page = array_pop($pathSegments);

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
		$this->contentTpl->addFilter('text2links');

		$html = $this->contentTpl->display();
		$this->html .= $html;
		return $html;
	}
}
