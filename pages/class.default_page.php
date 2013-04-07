<?php
class default_page extends page {

	public function __construct() {
		parent::__construct();

		$this->contentTpl = new vxPHP\Template\SimpleTemplate("{$this->currentPage}.htm");
		if(!empty($this->contentTpl->error) || $this->contentTpl->containsPHP()) {
			$this->generateHttpError();
		}
	}

	public function content() {
		$this->contentTpl->addFilter('text2links');

		$html = $this->contentTpl->display();
		$this->html .= $html;
		return $html;
	}
}
?>
