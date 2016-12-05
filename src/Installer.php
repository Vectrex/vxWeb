<?php

namespace App;

class Installer {
	
	public static function postCreateProject() {
		
		$rootDir = dirname(__DIR__);
		$publicDir = $rootDir . '/web';
		$vendorDir = $rootDir . '/vendor';

		// create ini dir cache
		
		if(!file_exists($rootDir . '/ini/.cache')) {
			mkdir($rootDir . '/ini/.cache', 0777);
		}
		else {
			chmod($rootDir . '/ini/.cache', 0777);
		}

		// symlink ckeditor and vxJS into public directory

		if(!file_exists($publicDir . '/js/vendor')) {
			mkdir($publicDir . '/js/vendor', 0755, TRUE);
		}

		if(!file_exists($publicDir . '/js/ckeditor')) {
			symlink($vendorDir . '/ckeditor', $publicDir . '/js/ckeditor');
		}
		
		if(!file_exists($publicDir . '/js/vendor/vxJS')) {

			if(!file_exists($publicDir . '/js/vendor')) {
				mkdir($publicDir . '/js/vendor', 0755);
			}

			symlink($vendorDir . '/vxjs/src', $publicDir . '/js/vendor/vxJS');

		}

		// generate web/files and view/default directories and/or set them writable for all 

		if(!file_exists($publicDir . '/files')) {
			mkdir($publicDir . '/files', 0777);
		}
		else {
			chmod($publicDir . '/files', 0777);
		}
		
		if(!file_exists($rootDir . '/view/default')) {
			mkdir($rootDir . '/view/default', 0777);
		}
		else {
			chmod($rootDir . '/view/default', 0777);
		}
		
	}
	
	public static function postInstall() {
		
		self::postCreateProject();
		
	}

	public static function postUpdate() {
	}

}