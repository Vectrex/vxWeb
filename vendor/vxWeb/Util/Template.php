<?php

namespace vxWeb\Util;

use vxPHP\File\FilesystemFolder;
use vxPHP\File\Exception\FilesystemFolderException;
use vxPHP\Application\Application;
use vxPHP\Application\Locale\Locale;
use vxPHP\Http\Request;
use vxWeb\Model\Page\Page;
use vxWeb\Model\Page\Revision;

/**
 * helper class to sync and update templates both in filesystem and database
 *
 * @author Gregor Kofler
 * @version 0.4.0 2015-06-19
 *
 */

class Template {

	private static $maxPageRevisions;
	
	/**
	 * public function for
	 * syncing file and db based templates
	 * 
	 * if file exists, but no db entry -> import and set active
	 * else if db entry exists, but no file -> export
	 * else if db entry and file exist ->
	 * 		if filemtime is older than active db entry -> export
	 * 		else if filemtime is newer than newest revision in db (not necessarily active) -> import and set active
	 * 		else if filemtime between active revision and newest revision -> export active revision
	 * 
	 * manually edited and saved templates are only prioritized if they are newer than any db entry  
	 */
	public static function syncTemplates() {

		$app		= Application::getInstance();
		$config		= $app->getConfig();
		$db			= $app->getDb();
		$locales	= $app->getAvailableLocales();

		// fetch file templates

		// universal templates

		$fileTpl = array();
		self::getTplFiles($fileTpl);

		// localized templates

		foreach($locales as $locale) {
			self::getTplFiles($fileTpl, $locale);
		}

		
		// update templates based on db info

		$pages = Page::getInstances();

		$pagesToExport = array();

		foreach($pages as $page) {

			$filename		= $page->getTemplate();
			$activeRevision	= $page->getActiveRevision();
			$newestRevision = $page->getNewestRevision();

			// file template not found

			if(!isset($fileTpl['universal'][$filename])) {

				// store revision id to create file template

				$pagesToExport[] = $page;

			}

			else {

				/*
				 * compare timestamps
				 * 
				 * do nothing, when timestamps match
				 * create initial revision when no revision exists; create page with initial revision when page does not exist 
				 * create new revision when active or newest revision in db are older than file template
				 * create new template file when file template is outdated
				 */

				if(
					$activeRevision && $activeRevision->getFirstCreated()->getTimestamp() === (int) $fileTpl['universal'][$filename]['fmtime']
				) {
					
					// do nothing

				}
				
				// create initial revision

				else if(!$newestRevision && !$activeRevision) {

					self::createPage($fileTpl['universal'][$filename]);

				}

				// add new revision

				else if(
					$activeRevision && $activeRevision->getFirstCreated()->getTimestamp() < $fileTpl['universal'][$filename]['fmtime'] ||
					$newestRevision && $newestRevision->getFirstCreated()->getTimestamp() < $fileTpl['universal'][$filename]['fmtime']
				) {

					// add revision

					if($activeRevision) {
						$newRevision = clone $activeRevision;
					}
					else {
						$newRevision = clone $newestRevision;
					}
					
					$newRevision
						->setMarkup(file_get_contents($fileTpl['universal'][$filename]['template']->getPath()))
						->activate()
						->save();

				}

				// store revision id to create file template

				else {

					$pagesToExport[] = $page;

				}
				
				// remove file template from hash
				
				unset($fileTpl['universal'][$filename]);

			}

		}

		// create db entries which are not present in database

		foreach($fileTpl as $locale => $templates) {

			foreach($templates as $filename => $template) {
				self::createPage($template);
			}

		}

		// create files by retrieving revision data
		
		foreach($pagesToExport as $page) {
			$page->exportActiveRevision();
		}

	}

	/**
	 * retrieve file templates for a given locale
	 * 
	 * @param array $fileTemplates
	 * @param Locale $locale
	 * @return array
	 */
	private static function getTplFiles(array &$fileTemplates, Locale $locale = NULL) {

		$app = Application::getInstance();
		$config = $app->getConfig();
		
		if(is_null($config->paths['editable_tpl_path'])) {
			throw new \Exception('No path for templates defined.');
		}
		
		$ndx	= $locale ? $locale->getLocaleId() : 'universal';
		$subdir	= $locale ? $locale->getLocaleId() . DIRECTORY_SEPARATOR : '';
		$path	= rtrim($app->getRootPath(), DIRECTORY_SEPARATOR) . $config->paths['editable_tpl_path']['subdir'] . $subdir;

		try {
			foreach(FilesystemFolder::getInstance($path)->getFiles('php') as $f) {

				$fi = $f->getFileInfo();

				$fileTemplates[$ndx][$f->getFilename()] = array(
					'template' => $f,
					'fmtime' => $fi->getMTime()
				);

			}
		}

		catch(FilesystemFolderException $e) {}
	}

	/**
	 * create page and a first revision
	 * if page already exists, just a revision is added
	 * 
	 * @param array $data template data
	 * @param string $locale of template
	 * @return boolean
	 */
	private static function createPage($data, $locale = NULL) {

		$db = Application::getInstance()->getDb();

		$alias = strtoupper(basename($data['template']->getFilename(), '.php'));

		$rows = $db->doPreparedQuery("SELECT pagesID from pages WHERE Alias = ?", array($alias));

		// insert only revision (locale might differ)

		if(!empty($rows)) {
			$page = Page::getInstance((int) $rows[0]['pagesid']);
		}

		else {
			$newId = $db->insertRecord('pages',
				array(
					'Template'	=> $data['template']->getFilename(),
					'Alias'		=> $alias
				)
			);
			$page = Page::getInstance((int) $newId);
		}

		$creationDate = new \DateTime();
		$creationDate->setTimestamp((int) $data['fmtime']);

		$revision = new Revision($page);
		$revision
			->setMarkup(file_get_contents($data['template']->getPath()))
			->setFirstCreated($creationDate)
			->activate()
			->save();
			
	}

	/**
	 * extract raw text data from template
	 */
	private static function extractRawtext($text) {
		return strip_tags(htmlspecialchars_decode(preg_replace(array('~\s+~', '~<br\s*/?>~', '~<\s*script.*?>.*?</\s*script\s*>~', '~<\?(php)?.*?\?>~'), array(' ', ' ', '', '') , $text)));
	}

	/**
	 * sanitize keywords, description
	 */
	private static function sanitizeTemplateData($data) {

		if(!empty($data['Keywords'])) {
			$data['Keywords']	= preg_replace(array('~\s+~', '~\s*,\s*~', '~[^ \w\däöüß,.-]~i'), array(' ', ', ', ''), trim($data['Keywords']));
		}

		if(!empty($data['Description'])) {
			$data['Description']= preg_replace(array('~\s+~', '~[^ \pL\d,.-]~'), array(' ', ''), trim($data['Description']));
		}

		return $data;
	}
}