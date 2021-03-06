<?php

namespace vxWeb\Model\Page;

use vxPHP\Application\Application;
use vxPHP\Observer\EventDispatcher;
use vxPHP\Observer\PublisherInterface;
use vxPHP\Observer\GenericEvent;

/**
 * Mapper class to handle revisioned pages, stored in table `pages`
 *
 * @author Gregor Kofler
 * @version 0.2.6 2018-11-21
 * 
 * @todo creation of new pages (several setters are superfluous ATM)
 */
class Page implements PublisherInterface {
	
	/**
	 * map of page instances indexed by their primary key
	 * 
	 * @var Page[]
	 */
	private static $instancesById;

	/**
	 * map of page instances indexed by their alias
	 * 
	 * @var Page[]
	 */
	private static $instancesByAlias;

	/**
	 * @var integer
	 */
	private $id;
	
	/**
	 * @var string
	 */
	private $alias;

	/**
	 * @var string
	 */
	private	$title;

	/**
	 * @var string
	 */
	private $keywords;

	/**
	 * @var string
	 */
	private $template;

	/**
	 * @var \DateTime
	 */
	private $firstCreated;
	
	/**
	 * @var \DateTime
	 */
	private $lastUpdated;

	/**
	 * constructor, currently unused
	 */
	public function __construct() {
	}

    /**
     * retrieve page instance identified by either its id (numeric) or alias (string)
     *
     * @param string|int $id
     * @return Page
     * @throws PageException
     * @throws \vxPHP\Application\Exception\ApplicationException
     */
	public static function getInstance($id) {
		
		if(is_numeric($id)) {
			$id = (int) $id;
			if(isset(self::$instancesById[$id])) {
				return self::$instancesById[$id];
			}

			$col = 'pagesID';
		}
		else {

			$id = strtolower($id);

			if(isset(self::$instancesByAlias[$id])) {
				return self::$instancesByAlias[$id];
			}

			$col = 'LOWER(Alias)';
		}

		$rows =  Application::getInstance()->getDb()->doPreparedQuery("
				SELECT
					*
				FROM
					pages
				WHERE
					$col = ?", [$id]);

		if(!$rows->valid()) {
			throw new PageException(sprintf("Page with %s '%s' does not exist.", $col, $id));
		}

		// generate and store instance

		$page = self::createInstance($rows->current());

		self::$instancesByAlias	[$page->alias]	= $page;
		self::$instancesById	[$page->id]		= $page;

		return $page;

	}

    /**
     * retrieve all currently stored pages
     *
     * @return Page[] :Page
     * @throws \vxPHP\Application\Exception\ApplicationException
     */
	public static function getInstances() {

		foreach(Application::getInstance()->getDb()->doPreparedQuery("
			SELECT
				*
			FROM
				pages
		") as $row) {

			if(!isset(self::$instancesById[(int) $row['pagesid']])) {

				$page = self::createInstance($row);

				self::$instancesByAlias	[$page->alias]	= $page;
				self::$instancesById	[$page->id]		= $page;

			}
		}
		
		return self::$instancesById;
		
	}

	/**
	 * create page and set all attributes stored in $data
	 * 
	 * @param array $data
	 * @return Page
	 */
	private static function createInstance(array $data) {

		$page = new self();

		// set identification
		
		$page->id		= (int) $data['pagesid'];
		$page->alias	= $data['alias'];

		// set dates

		if(!empty($data['firstcreated'])) {
			$page->firstCreated = new \DateTime($data['firstcreated']);
		}
		
		if(!empty($data['lastupdated'])) {
			$page->lastUpdated = new \DateTime($data['lastupdated']);
		}

		// set various text fields

		$page->setTitle($data['title']);
		$page->setKeywords($data['keywords']);
		$page->setTemplate($data['template']);

		// get revisions

		return $page;

	}

	/**
	 * get all revisions
	 * 
	 * @return multitype:Revision
	 */
	public function getRevisions() {

		return Revision::getInstancesForPage($this);

	}

	/**
	 * get active revision
	 * returns NULL when no active revision is found
	 * 
	 * @return Revision
	 */
	public function getActiveRevision() {

		$revisions = $this->getRevisions();
		
		// proceed when revisions were found at all
		
		if($revisions) {
		
			foreach($revisions as $revision)  {

				if($revision->isActive()) {
					return $revision;
				}

			}

		}

	}

	/**
	 * sort revisions and return revision with latest creation date
	 * @return Revision
	 * 
	 * @todo do sorting only once 
	 */
	public function getNewestRevision() {
		
		$revisions = $this->getRevisions();

		// proceed when revisions were found at all

		if($revisions) {

			usort($revisions, function (Revision $a, Revision $b) {
	
				$tsa = $a->getFirstCreated()->format(\DateTime::W3C);
				$tsb = $b->getFirstCreated()->format(\DateTime::W3C);
				if($tsa === $tsb) {
					return 0;
				}
				
				// sort descending
	
				return $tsa < $tsb ? 1 : -1;
	
			});
			
			return $revisions[0];

		}

	}

	/**
	 * sort revisions and return revision with earliest creation date
	 * @return Revision
	 * 
	 * @todo do sorting only once
	 */
	public function getOldestRevision() {

		$revisions = $this->getRevisions();

		// proceed when revisions were found at all

		if($revisions) {

			usort($revisions, function (Revision $a, Revision $b) {
			
				$tsa = $a->getFirstCreated()->format(\DateTime::W3C);
				$tsb = $b->getFirstCreated()->format(\DateTime::W3C);
				if($tsa === $tsb) {
					return 0;
				}
					
				// sort ascending
			
				return $tsa > $tsb ? 1 : -1;

			});

			return $revisions[0];

		}

	}

	/**
	 * retrieve a revision identified by a creation timestamp 
	 * @param \DateTime $dateTime
	 * @return Revision
	 */
	public function getRevisionByDateTime(\DateTime $dateTime) {
		
		foreach($this->getRevisions() as $revision) {
			
			if($revision->getFirstCreated() && $revision->getFirstCreated()->format(\DateTime::W3C) === $dateTime->format(\DateTime::W3C)) {
				return $revision;
			}

		}

	}

    /**
     * exports the active revision of the page to its template file
     * path information is retrieved from the config object
     * the modification timestamp of the generated file is set to creation timestamp of the active revision
     *
     * @throws PageException
     * @throws \vxPHP\Application\Exception\ApplicationException
     */
	public function exportActiveRevision() {

		// dispatch 'beforePageRevisionExport' event to inform optional listeners

		EventDispatcher::getInstance()->dispatch(new GenericEvent('beforePageRevisionExport', $this));

		$app	= Application::getInstance();
		$config	= $app->getConfig();
		
		if(is_null($config->paths['editable_tpl_path'])) {
			throw new PageException('No export path for templates defined.');
		}
		
		$revision = $this->getActiveRevision();
		
		if(is_null($revision)) {
			throw new PageException(sprintf("No active revision for template '%s' found.", $this->getAlias()));
		}

		$locale = $revision->getLocale();

		$path =
			rtrim($app->getRootPath(), DIRECTORY_SEPARATOR) .
			$config->paths['editable_tpl_path']['subdir'] .
			($locale ? $locale->getLocaleId() . DIRECTORY_SEPARATOR : '') .
			$this->getTemplate();

		if(!($handle = fopen($path, 'w'))) {
			throw new PageException(sprintf("Cannot export template '%s'. '%s' not writable.", $this->getAlias(), $path));
		}

		if(FALSE === fwrite($handle, $revision->getMarkup())) {
			throw new PageException(sprintf("Cannot export template '%s'. Creating '%s' failed.", $this->getAlias(), $path));
		}

		fclose($handle);

		if(!@chmod($path, 0666) || !@touch($path, $revision->getFirstCreated()->getTimestamp())) {
			throw new PageException(sprintf("Cannot set mode or timestamp of template file '%s'.", $path));
		}

		// dispatch 'afterPageRevisionExport' event to inform optional listeners

		EventDispatcher::getInstance()->dispatch(new GenericEvent('afterPageRevisionExport', $this));

	}

	/**
	 * get id
	 * @return integer
	 */
	public function getId() {

		return $this->id;

	}
	
	/**
	 * get alias
	 * @return string
	 */
	public function getAlias() {

		return $this->alias;

	}
	
	/**
	 * get title
	 * @return string
	 */
	public function getTitle() {

		return $this->title;

	}

	/**
	 * set title
	 * @param string $title
	 * @return Page
	 */
	public function setTitle($title) {

		$this->title = $title;
		return $this;

	}
	
	/**
	 * get keywords
	 * @return string
	 */
	public function getKeywords() {

		return $this->keywords;

	}

	/**
	 * set keywords
	 * @param string $keywords
	 * @return Page
	 */
	public function setKeywords($keywords) {

		$this->keywords = $keywords;
		return $this;

	}

	/**
	 * get template filename
	 * @return string
	 */
	public function getTemplate() {

		return $this->template;

	}
	
	/**
	 * set template filename
	 * @param string $template
	 * @return Page
	 */
	public function setTemplate($template) {

		$this->template = $template;
		return $this;

	}

}