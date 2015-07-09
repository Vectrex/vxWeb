<?php

namespace vxWeb;

use vxPHP\Service\Service;
use vxPHP\Application\Application;

/**
 * a simple proxy class to provide
 * HTMLPurifier as service
 * searches for the HTMLPurifier standalone version in
 * <application_root>/vendor/HTMLPurifier
 * 
 * @author Gregor Kofler
 * @version 0.1.0 2015-07-09
 */
class HTMLPurifierProxy extends Service {
	
	protected $htmlPurifierInstance;

	public function __construct() {
	}
	
	public function purify($html) {

		if(!$this->htmlPurifierInstance) {

			require Application::getInstance()->getRootPath() .
			'vendor' . DIRECTORY_SEPARATOR .
			'HTMLPurifier' . DIRECTORY_SEPARATOR .
			'HTMLPurifier.standalone.php';

			$config = \HTMLPurifier_Config::createDefault();
			
			foreach($this->parameters as $name => $value) {
				$config->set($name, $value);
			}

			$this->htmlPurifierInstance = new \HTMLPurifier($config);
		}
		
		return $this->htmlPurifierInstance->purify($html);
		
	}
	
}