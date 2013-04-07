<?php

use vxPHP\Webpage\Webpage;
use vxPHP\Util\Rex;
use vxPHP\Template\SimpleTemplate;

class page extends Webpage {

	protected $pageRequests = array(
		'offset' => Rex::INT_EXCL_NULL
	);
	protected $requestDefaults = array(
		'offset' => 1
	);

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

	/**
	 * generate a list of sub page links
	 *
	 * needs
	 * $this->totalEntries set to the total count of entries
	 * $this->validatedRequest['offset'] is reserved for current sub page
	 *
	 * @param int $pageEntries number of entries per page
	 * @param int $range number of listet sub pages in menu; null means all pages
	 * @param mixed $separator
	 * contains markup for [start][previous][separator][next][last];
	 * if single character: used for separator of sub-page links
	 */
	protected function pageListMenu($pageEntries = 5, $range = null, $separator = ' | ') {

		if(empty($this->totalEntries)) { return; }

		$totalPages	= ceil($this->totalEntries/$pageEntries);

		$page	= (int) (isset($this->validatedRequests['offset']) ? $this->validatedRequests['offset'] : 1);

		$get = $this->validatedRequests;
		unset($get['offset']);
		foreach($get as $k => $v) {
			if($v === '') {
				unset($get[$k]);
				continue;
			}
			$get[$k] = "$k=$v";
		}

		$uri = $this->config->getDocument().'?'.implode('&', $get).(empty($get) ? '?' : '&').'offset=';

		$pageLinks	= array();

		if(empty($range)) {
			for($i = 1; $i <= $totalPages; $i++) {
				$pageLinks[] = $i != $page ? SimpleTemplate::a($uri.$i, $i) : "<span class='currentPage'>$i</span>";
			}
			if(is_string($separator))							{ return implode($separator, $pageLinks); }
			if(is_array($separator) && count($separator) == 5)	{ return implode($separator[2], $pageLinks); }
			return implode(' | ', $pageLinks);
		}
		else {
			$range -= 1;
			$min = max(1, $page-ceil($range/2));
			$max = min($totalPages, $page+floor($range/2));
			$addMax = abs(min($page-ceil($range/2)-1, 0));
			$subMin = max($page+floor($range/2), $totalPages)-$totalPages;
			$min = max(1, $min-$subMin);
			$max = min($totalPages, $max+$addMax);

			for($i = $min; $i <= $max; $i++) {
				$pageLinks[] = $i != $page ? SimpleTemplate::a($uri.$i, $i) : "<span class='currentPage'>$i</span>";
			}
			$sep = is_array($separator) && count($separator) == 5;

			$right  = $max >= $totalPages ? '' : SimpleTemplate::a($uri.($max+1), $sep ? $separator[3] : '&raquo;');
			$end	= $max >= $totalPages-1 ? '' : SimpleTemplate::a($uri.($totalPages), $sep ? $separator[4] : '&nbsp;]');

			$left	= $min <= 1 ? '' : SimpleTemplate::a($uri.($min-1), $sep ? $separator[1] : '&laquo;');
			$start	= $min <= 2 ? '' : SimpleTemplate::a($uri.'1', $sep ? $separator[0] : '[&nbsp;');

			if(is_string($separator))	{ return "$start$left&nbsp;".implode($separator, $pageLinks)."&nbsp;$right$end"; }
			if($sep)					{ return "$start$left&nbsp;".implode($separator[2], $pageLinks)."&nbsp;$right$end"; }
			return "$start$left&nbsp;".implode(' | ', $pageLinks)."&nbsp;$right$end";
		}
	}

	protected function handleHttpRequest() {
	}
}
?>