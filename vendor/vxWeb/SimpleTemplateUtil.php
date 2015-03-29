<?php

namespace vxWeb;

use vxPHP\File\FilesystemFolder;
use vxPHP\File\Exception\FilesystemFolderException;
use vxPHP\Application\Application;
use vxPHP\Http\Request;

/**
 * helper class to sync and update templates both in filesystem and database
 *
 * @author Gregor Kofler
 * @version 0.3.9 2013-10-05
 *
 * @todo re-establish handling of localized templates
 *
 */

class SimpleTemplateUtil {

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

		$config		= Application::getInstance()->getConfig();
		$db			= Application::getInstance()->getDb();

		$locales	= array();

		// fetch file templates

		$fileTpl = self::getTplFiles();

		// fetch db templates

		$dbTpl = array();

		foreach (
			$db->doPreparedQuery("
				SELECT
					sq.*,
					ar.revisionsID,
					UNIX_TIMESTAMP(ar.templateUpdated) AS activeUpdatedTS
				FROM
					(
						SELECT
							p.pagesID,
							p.Template,
							p.Alias,
							UNIX_TIMESTAMP(max(r.templateUpdated)) AS newestUpdateTS
						FROM
							pages p
							LEFT JOIN revisions r ON r.pagesID = p.pagesID
						WHERE
							r.Locale IS NULL
							OR r.Locale = ''
						GROUP BY
							pagesID,
							Template,
							Alias
					)
					AS sq
					LEFT JOIN revisions ar ON ar.pagesID = sq.pagesID AND ar.active = 1			
			")
			as $r) {

				$dbTpl['universal'][$r['Template']] = $r;
		}

		// localized templates

		foreach($locales as $l) {

			$rows = $db->doPreparedQuery("
				SELECT
					sq.*,
					ar.revisionsID,
					UNIX_TIMESTAMP(ar.templateUpdated) AS activeUpdatedTS
				FROM
					(
						SELECT
							p.pagesID,
							p.Template,
							p.Alias,
							UNIX_TIMESTAMP(max(r.templateUpdated)) AS newestUpdateTS
						FROM
							pages p
							LEFT JOIN revisions r ON r.pagesID = p.pagesID
						WHERE
							r.Locale = ?
						GROUP BY
							pagesID,
							Template,
							Alias
					)
					AS sq
					LEFT JOIN revisions ar ON ar.pagesID = sq.pagesID AND ar.active = 1			
				", array($l));

			foreach($rows as $r) {
				$dbTpl[$l][$r['Template']] = $r;
			}
		}

		// update templates based on db info

		$revisionIdsToCreate = array();

		foreach($dbTpl as $locale => $templates) {

			foreach($templates as $filename => $template) {

				// file template not found
	
				if(!isset($fileTpl[$locale][$filename])) {
	
					// store revision id to create file template
	
					$revisionIdsToCreate[] = $template['revisionsID'];
	
				}
	
				else {
	
					// compare timestamps
	
					// active template of db or newest template is newer than file template
	
					if(
						(int) $template['activeUpdatedTS'] > $fileTpl[$locale][$filename]['fmtime'] ||
						(int) $template['newestUpdatedTS'] > $fileTpl[$locale][$filename]['fmtime']
					) {
	
						// store revision id to create file template
						
						$revisionIdsToCreate[] = $template['revisionsID'];
						
					}
					
					// file template is newer than both active and newest db template
	
					else {
	
						// add revision
	
						self::updateTemplate(array_merge($template, $fileTpl[$locale][$filename]), $locale);
	
					}
					
					// remove file template from hash
					
					unset($fileTpl[$locale][$filename]);

				}
	
			}

			// create db entries which are not present in database

			foreach($fileTpl as $locale => $templates) {

				foreach($templates as $filename => $template) {
					self::insertTemplate($template, $locale);
				}

			}

		}
		
		// create files by retrieving revision data
		
		foreach($revisionIdsToCreate as $revisionId) {
			self::createTemplateFromRevision($revisionId);
		}

	}

	/**
	 * retrieve metadata of template stored in database
	 *
	 * @param string $pageId
	 */
	public static function getPageMetaData($pageId, $locale = '') {

		if(($db = Application::getInstance()->getDb())) {

			if($db->tableExists('pages') && $db->tableExists('revisions')) {

				$data = $db->doPreparedQuery("
					SELECT
						r.Title,
						a.Name,
						r.Keywords,
						r.Description,
						r.templateUpdated as lastChanged,
						IFNULL(r.locale, '') AS locale_sort
					FROM
						revisions r
						INNER JOIN pages p ON r.pagesID = p.pagesID
						LEFT JOIN admin a ON r.authorID = a.adminID
					WHERE
						p.Alias = ? AND
						r.locale IS NULL OR r.locale = ?
					ORDER BY
						locale_sort DESC, active DESC, r.lastUpdated DESC
					LIMIT 1
					",
					array(
						strtoupper($pageId),
						$locale
					)
				);

				if(!empty($data[0])) {
					unset($data[0]['locale_sort']);
					return $data[0];
				}
			}
		}
	}

	/**
	 * public function for
	 * adding a revision to a template
	 * @param array $data new revision data
	 */
	public static function addRevision($data) {

		$locale = $data['Locale'];
		unset($data['Locale']);
		self::deleteOldRevisions($data['pagesID'], $locale);
		return self::insertRevision($data, $locale);

	}

	/**
	 * retrieve template files
	 * @param unknown $locale
	 * @param unknown $tpl
	 */
	private static function getTplFiles() {

		$tpl = array('universal' => array());

		$path	= self::getPath();

		try {
			$files	= FilesystemFolder::getInstance($path)->getFiles('php');
		}
		catch(FilesystemFolderException $e) {
			return;
		}

		foreach($files as $f) {

			$fi = $f->getFileInfo();

			$tpl['universal'][$f->getFilename()] = array(
				'template' => $f,
				'fmtime' => $fi->getMTime()
			);
		}

		return $tpl;
	}

	/**
	 * create a new template file from a db entry
	 * 
	 * @param int $revisionId
	 * @return boolean
	 */
	private static function createTemplateFromRevision($revisionId) {

		$rows = Application::getInstance()->getDb()->doPreparedQuery("
			SELECT
				r.Markup,
				r.Template
			FROM
				revisions r
			WHERE
				revisionsID = ?
			", array((int) $revisionId)
		);

		$path = self::getPath() . $rows[0]['Template'];

		if(!($handle = fopen($path, 'w'))) {
			return FALSE;
		}
		if(fwrite($handle, $rows[0]['Markup']) === FALSE) {
			return FALSE;
		}
		fclose($handle);

		@chmod($path, 0666);
		@touch($path, $rows[0]['lastUpdateTS']);

		return TRUE;
	}

	/**
	 * insert page data and first revision
	 * 
	 * @param array $data template data
	 * @param string $locale of template
	 * @return boolean
	 */
	private static function insertTemplate($data, $locale = 'universal') {

		$db = Application::getInstance()->getDb();

		$alias = strtoupper(basename($data['template']->getFilename(), '.php'));

		$rows = $db->doPreparedQuery("SELECT pagesID from pages WHERE Alias = ?", array($alias));

		// insert only revision (locale might differ)

		if(!empty($rows)) {
			$newId = $rows[0]['pagesID'];
		}
		else {
			$newId = $db->insertRecord('pages',
				array(
					'Template'	=> $data['template']->getFilename(),
					'Alias'		=> $alias
				)
			);
		}

		$markup = file_get_contents($data['template']->getPath());

		return self::insertRevision(
			array(
				'Markup'			=> $markup,
				'Rawtext'			=> self::extractRawtext($markup),
				'pagesID'			=> $newId,
				'templateUpdated'	=> date('Y-m-d H:i:s', (int) $data['fmtime'])
			), $locale
		);
	}

	/**
	 * delete old revisions and add new revision
	 */
	private static function updateTemplate($data, $locale = 'universal') {

		$metaData = self::getPageMetaData($data['Alias']);
		$markup = file_get_contents($data['Template']->getPath());

		self::deleteOldRevisions($data['pagesID'], $locale);

		return self::insertRevision(
			array_merge($metaData,
			array(
				'Markup' => $markup,
				'Rawtext' => self::extractRawtext($markup),
				'pagesID' => $data['pagesID'],
				'templateUpdated' => $data['fmtimef']
			)), $locale
		);
	}

	private static function insertRevision($row, $locale = 'universal') {

		$config	= Application::getInstance()->getConfig();
		$db		= Application::getInstance()->getDb();

		$row			= self::sanitizeTemplateData($row);
		$row['Rawtext']	= self::extractRawtext($row['Markup']);

		$parameters = array((int) $row['pagesID']);

		if(!empty($locale) && $locale != 'universal' && in_array($locale, $config->site->locales)) {
			$row['Locale'] = $locale;
			$localeSQL = 'r.Locale = ?';
			$parameters[] = $locale;
		}
		else {
			$localeSQL = "(r.Locale IS NULL OR r.Locale = '')";
		}

		$db->execute('
			UPDATE
				revisions r
			SET
				active = NULL
			WHERE
				active = 1 AND
				pagesID = ? AND ' .
				$localeSQL
			,$parameters
		);

		$row['active'] = 1;

		return $db->insertRecord('revisions', $row);
	}

	private static function deleteOldRevisions($pagesID, $locale = 'universal') {

		$config	= Application::getInstance()->getConfig();
		$db		= Application::getInstance()->getDb();

		if(empty(self::$maxPageRevisions)) {
			self::$maxPageRevisions =	isset($config->site->max_page_revisions) &&
										is_numeric($config->site->max_page_revisions) ?
										((int) $config->site->max_page_revisions > 0 ? (int) $config->site->max_page_revisions : 1) :
										5;
		}

		$parameters = array((int) $pagesID);
		
		if(!empty($locale) && $locale != 'universal' && in_array($locale, $config->site->locales)) {
			$localeSQL = 'Locale = ?';
			$parameters[] = $locale;
		}
		else {
			$localeSQL = "(Locale IS NULL OR Locale = '')";
		}
		
		// get all revisions sorted from new to old

		$rows = $db->doPreparedQuery('
			SELECT
				revisionsID
			FROM
				revisions r
			WHERE
				pagesID = ? AND ' .
				$localeSQL . '
			ORDER BY
				templateUpdated DESC
			', $parameters
		);

		if(count($rows) < self::$maxPageRevisions) {
			return TRUE;
		}

		$keepsIds = array();

		// remove old revisions

		for($i = 0; $i < self::$maxPageRevisions - 1; ++$i) {
			$keepIds[] = $rows[$i]['revisionsID'];
		}

		return $db->execute('
			DELETE FROM
				revisions
			WHERE
				revisionsID NOT IN (' .
				implode(', ', array_fill(0, count($keepIds), '?')) .
				') AND pagesID = ? AND '.
				$localeSQL,
			array_merge($keepIds, $parameters)
		);
		
	}

	/**
	 * get path to editable templates
	 *
	 * @throws \Exception
	 * @return string
	 */
	private static function getPath() {

		$app = Application::getInstance();
		$config = $app->getConfig();

		if(is_null($config->paths['editable_tpl_path'])) {
			throw new \Exception('No path for templates defined.');
		}

		return
			rtrim($app->getRootPath()) .
			$config->paths['editable_tpl_path']['subdir'];
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