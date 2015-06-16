<?php

namespace vxWeb\Orm\Page;

use vxPHP\User\User;
use vxPHP\Application\Application;
use vxPHP\Application\Locale\Locale;

/**
 * Mapper class for page revisions, stored in table `revisions`
 *
 * @author Gregor Kofler
 * @version 0.3.0 2015-06-16
 * 
 * @todo retrieve and save locale
 * @todo attribute sanitation
 */
class Revision {

	/**
	 * @var array[vxWeb\Orm\Revision]
	 */
	private static $instancesById;
	/**
	 * @var array[vxWeb\Orm\Revision]
	 */
	private static $instancesByPage;
	
	/**
	 * primary key of record
	 * @var integer
	 */
	private $id;

	/**
	 * parent page object
	 * @var Page
	 */
	private $page;
	
	/**
	 * user associated with this revision
	 * @var User 
	 */
	private $author;
	
	/**
	 * locale of template
	 * @var Locale
	 */
	private $locale;

	/**
	 * @var \DateTime
	 */
	private $templateUpdated;
	
	/**
	 * @var \DateTime
	 */
	private $lastUpdated;
	
	/**
	 * @var \DateTime
	 */
	private $firstCreated;

	/**
	 * flag which indicates, whether the revision is the currently active
	 * @var boolean
	 */
	private $active;

	/**
	 * @var string
	 */
	private $title;
	
	/**
	 * @var string
	 */
	private $keywords;
	
	/**
	 * @var string
	 */
	private $description;
	
	/**
	 * @var string
	 */
	private $markup;

	/**
	 * @var array
	 */
	private $monitoredAttributes = array('title', 'keywords', 'description', 'markup');
	
	/**
	 * hash of saved data, to allow quick check for updated data
	 * 
	 * @var string
	 */
	private $savedDataHash;

	/**
	 * set to TRUE when markup contains server-side code
	 * @var boolean
	 */
	private $containsPHP;
	
	/**
	 * get revision by its primary key
	 * 
	 * @param integer $id
	 * @throws PageException
	 * @return multitype:Revision
	 */
	public static function getInstance($id) {
		
		// return instance when previously instantiated
		
		if(isset(self::$instancesById[$id])) {
			
			return self::$instancesById[$id];

		}
		
		// get db data

		$rows = Application::getInstance()->getDb()->doPreparedQuery("
			SELECT
				*
			FROM
				revisions
			WHERE
				revisionsID = ?", array($id)
		);
		
		if(!count($rows)) {
			throw new PageException(sprintf("Revision with revisionsID '%s' does not exist.", $id));
		}

		// get Page instance; page has revisions not retrieved yet, otherwise instance would have been found

		$page = Page::getInstance($rows[0]['pagesID']);

		// create instance and cache it

		$revision = self::createRevision($page, $rows[0]);
		self::$instancesById[$revision->id] = $revision;

		return $revision;

	}
	
	/**
	 * instantiate all revisions of a page
	 * 
	 * @param Page $page
	 * @return multitype:Revision
	 */
	public static function getInstancesForPage(Page $page) {
		
		$pageId = $page->getId();

		if(is_null($pageId) || isset(self::$instancesByPage[$pageId])) {
			
			return self::$instancesByPage[$pageId];

		}
		
		$rows = Application::getInstance()->getDb()->doPreparedQuery("
			SELECT
				*
			FROM
				revisions
			WHERE
				pagesID = ?", array($pageId)
		);

		$instances = array();
		
		// generate and store instance

		foreach($rows as $row) {
			
			if(isset(self::$instancesById[$row['revisionsID']])) {
				$revision = self::$instancesById[$row['revisionsID']];
			}
			else {
				$revision = self::createRevision($page, $row);
				self::$instancesById[$revision->id] = $revision;
			}

			$instances[] = $revision;

		}
		
		self::$instancesByPage[$pageId] = $instances;
		
		return $instances;

	}
	
	/**
	 * create a new revision "linked" to $page
	 * 
	 * @param Page $page
	 * @param array $data
	 * @return Revision
	 */
	private static function createRevision (Page $page, array $data) {

		$data = array_change_key_case($data, CASE_LOWER);
		
		$revision = new self($page);

		$revision->id				= (int) $data['revisionsid'];
		$revision->active			= !!$data['active'];
		$revision->author			= $data['authorid']			? User::getInstance($data['authorid'])		: NULL;

		$revision->firstCreated		= $data['firstcreated']		? new \DateTime($data['firstcreated'])		: NULL;
		$revision->lastUpdated		= $data['lastupdated']		? new \DateTime($data['lastupdated'])		: NULL;
		$revision->templateUpdated	= $data['templateupdated']	? new \DateTime($data['templateupdated'])	: NULL;
		
		foreach($revision->monitoredAttributes as $attr) {
			$revision->$attr = $data[$attr];
		}

		$revision->updateHash();

		return $revision;
	}

	/**
	 * Constructor
	 * a "linked" page is a required attribute
	 * 
	 * @param Page $page
	 */
	public function __construct(Page $page) {

		$this->page = $page;

	}
	
	/**
	 * clone revision
	 * unset id, to allow subsequent saving
	 */
	public function __clone() {
		
		$this->id			= NULL;
		$this->lastUpdated	= NULL;
		$this->firstCreated	= NULL;

		$this->updateHash();
		
	}
	
	/**
	 * reset active flag
	 * 
	 * make change persistent, if revision is already saved
	 * a previously active revision is automatically deactivated
	 */
	public function activate() {
		
		$currentlyActive = $this->page->getActiveRevision();

		if($this !== $currentlyActive) {

			if($currentlyActive) {
				$currentlyActive->deActivate();
			}

			$this->active = TRUE;
			
			if($this->id) {
					
				Application::getInstance()->getDb()->updateRecord(
					'revisions',
					$this->id,
					array (
						'active' => (int) $this->active
					)
				);
			
			}

		}
		
		return $this;

	}

	/**
	 * reset active flag
	 * 
	 * make change persistent, if revision is already saved
	 */
	public function deactivate() {

		$this->active = FALSE;

		if($this->id) {
			
			Application::getInstance()->getDb()->updateRecord(
				'revisions',
				$this->id,
				array (
					'active' => NULL
				)
			);

		}
		
		return $this;

	}

	/**
	 * make changes persistent
	 * saving of a revision happens only once
	 * further updates are done by inserting new revisions
	 * 
	 * @throws PageException
	 * @return Revision
	 */
	public function save() {
		
		if(isset($this->id)) {
			throw new PageException('Cannot save a previously saved revision');
		}

		// @FIXME: firstCreated contains current timestamp, not the one when written

		$this->firstCreated = new \DateTime();

		$toSave = array();

		foreach($this->monitoredAttributes as $attr) {
			
			$toSave[$attr] = $this->$attr;
			
		}

		// save by inserting new revision record

		$this->id = (int) Application::getInstance()->getDb()->insertRecord('revisions', array_merge(
			$toSave, array(
				'pagesID'		=> $this->page->getId(),
				'authorID'		=> $this->author ? $this->author->getAdminId() : NULL,
				'active'		=> (int) $this->active
			)
		));

		// add to id map

		self::$instancesById[$this->id] = $this;
		
		// add to page map, if present
		
		if(!is_null(self::$instancesByPage[$this->page->getId()])) {
			self::$instancesByPage[$this->page->getId()][] = $this;
		}

		return $this;

	}
	
	/**
	 * delete a revision
	 * remove record in database (when already saved)
	 * and entries in both id an page map 
	 */
	public function delete() {
		
		if($this->id) {
			Application::getInstance()->getDb()->deleteRecord('revisions', $this->id);
			unset(self::$instancesById[$this->id]);
		}

		foreach(self::$instancesByPage[$this->page->getId()] as $ndx => $revision) {
			if($revision === $this) {
				array_splice(self::$instancesByPage[$this->page->getId()], $ndx, 1);
				break;
			}
		} 
		
	}

	/**
	 * get primary key of revision
	 * @return integer
	 */
	public function getId() {

		return $this->id;

	}

	/**
	 * get "parent" page of revision
	 * @return Page
	 */
	public function getPage() {

		return $this->page;

	}

	/**
	 * get author of revision
	 * @return \vxPHP\User\User
	 */
	public function getAuthor() {

		return $this->author;

	}

	/**
	 * set author
	 * @param User $author
	 * @return Revision
	 */
	public function setAuthor(User $author) {

		$this->author = $author;
		return $this;

	}

	/**
	 * get stored template update time
	 * @return DateTime
	 */
	public function getTemplateUpdated() {

		return $this->templateUpdated;

	}

	/**
	 * get stored timestamp of last update
	 * this timestamp is set by DB wrapper or db itself
	 * @return DateTime
	 */
	public function getLastUpdated() {

		return $this->lastUpdated;

	}

	/**
	 * get stored timestamp of record creation
	 * this timestamp is set by DB wrapper
	 * @return DateTime
	 */
	public function getFirstCreated() {

		return $this->firstCreated;

	}

	/**
	 * get active status of revision
	 * @return boolean
	 */
	public function isActive() {

		return $this->active;

	}

	/**
	 * activate or deactivate revision
	 * @param boolean $active
	 * @return Revision
	 */
	public function setActive($active) {
		
		if($active) {
			$this->activate();
		}
		else {
			$this->deactivate();
		}
		return $this;

	}

	/**
	 * returns TRUE when markup contains PHP code 
	 * @return boolean
	 */
	public function containsPHP() {

		if(is_null($this->containsPHP)) {
			$this->setContainsPHP();
		}

		return $this->containsPHP;

	}
	
	/**
	 * get title
	 * @param string $default
	 * @return string
	 */
	public function getTitle($default = NULL) {

		if(!is_null($this->title)) {
			return $this->title;
		}
		return $default;

	}

	/**
	 * set title
	 * @param string $title
	 * @return Revision
	 */
	public function setTitle($title) {

		$this->title = $title;
		return $this;

	}

	/**
	 * get keywords
	 * @param string $default
	 * @return string
	 */
	public function getKeywords($default = NULL) {

		if(!is_null($this->keywords)) {
			return $this->keywords;
		}
		return $default;

	}

	/**
	 * set keywords
	 * @param string $keywords
	 * @return Revision
	 */
	public function setKeywords($keywords) {

		$this->keywords = $keywords;
		return $this;

	}

	/**
	 * get description
	 * @param string $default
	 * @return string
	 */
	public function getDescription($default = NULL) {

		if(!is_null($this->description)) {
			return $this->description;
		}

	}

	/**
	 * set description
	 * @param string $description
	 * @return Revision
	 */
	public function setDescription($description) {

		$this->description = $description;
		return $this;

	}

	/**
	 * get locale
	 * @return Locale
	 */
	public function getLocale() {

		return $this->locale;

	}

	/**
	 * set locale
	 * @param string $markup
	 * @return Revision
	 */
	public function setLocale(Locale $locale) {

		$this->locale = $locale;
		return $this;

	}

	/**
	 * get markup
	 * @return string
	 */
	public function getMarkup() {

		return $this->markup;

	}

	/**
	 * set markup
	 * @param string $markup
	 * @return Revision
	 */
	public function setMarkup($markup) {

		$this->markup = $markup;
		$this->setContainsPHP();

		return $this;

	}

	/**
	 * returns true when one of monitoredAttributes
	 * of an already saved revision was changed
	 * check is done by calculating and comparing a hash
	 * 
	 * @return boolean
	 */
	public function wasChanged() {
		
		$hash = '';
		
		foreach($this->monitoredAttributes as $attr) {
			$hash .= $this->$attr;
		}
		
		return $this->savedDataHash !== sha1($hash);
		
	}

	/**
	 * calculate hash to detect changes to monitoredAttributes
	 */
	private function updateHash() {

		$hash = '';

		foreach($this->monitoredAttributes as $attr) {
			$hash .= $this->$attr;
		}
		
		$this->savedDataHash = sha1($hash);
	}
	
	/**
	 * check markup whether it contains <? or <?php and an optional closing tag
	 */
	private function setContainsPHP() {

		$this->containsPHP = !preg_match('~\\<\\?(\\s+|php).*?(\\?\\>)~is', $this->markup);

	}

}