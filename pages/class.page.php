<?php

use vxPHP\Webpage\Webpage;

class page extends Webpage {

	public function __construct() {
		$this->compressJS = FALSE;
		parent::__construct();
	}

	public function pageHeader() {
		$html = '
			<div id="header"></div>';
		$this->html .= $html;
		return $html;
	}

	public function pageFooter() {
		$html = '';
		$this->html .= $html;
		return $html;
	}

	public function content() {
		$html = '<p>Noch nicht implementiert.</p>';
		$this->html .= $html;
		return $html;
	}

	protected function handleHttpRequest() {
	}
}
