<?php

namespace Maestro\conductor;

/**
 * Page provider implemented as map.
 */
class Mro_PageConfig extends Mro_PageMap {
	
	function __construct() {
		parent::__construct();
	}
	
	function addIncludedPage($pageId, $pageFileName, $isDefaultPage = false, $isErrorPage = false) {
		$page = new Mro_IncludedPage($pageId, $pageFileName, null);
		$this->put($page);
		return $page;
	}
	
	function addIncludedPageWithLogic($pageId, $pageFileName, $pageLogic) {
		$page = new Mro_IncludedPage($pageId, $pageFileName, $pageLogic);
		$this->put($page);
		return $page;
	}
}