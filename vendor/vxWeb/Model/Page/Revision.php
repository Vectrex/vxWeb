<?php

namespace vxWeb\Model\Page;

use vxPHP\Application\Application;
use vxPHP\Application\Exception\ApplicationException;
use vxPHP\Application\Locale\Locale;

/**
 * Mapper class for page revisions, stored in table `revisions`
 *
 * @author Gregor Kofler
 * @version 0.4.2 2021-05-22
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
	 * user id which authored/updated this revision
	 * @var integer
	 */
	private $authorId;
	
	/**
	 * locale of template
	 * @var Locale
	 */
	private $locale;

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
	private $monitoredAttributes = ['title', 'keywords', 'description', 'markup'];
	
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
     * @return Revision
     * @throws PageException
     * @throws ApplicationException
     */
	public static function getInstance(int $id): self {
		
		// return instance when previously instantiated
		
		if(isset(self::$instancesById[$id])) {
			
			return self::$instancesById[$id];

		}
		
		// get db data

		$rows = Application::getInstance()->getVxPDO()->doPreparedQuery("
			SELECT
				*
			FROM
				revisions
			WHERE
				revisionsID = ?", [$id]
		);
		
		if(!count($rows)) {
			throw new PageException(sprintf("Revision with revisionsID '%s' does not exist.", $id));
		}

		// get Page instance; page has revisions not retrieved yet, otherwise instance would have been found

		$page = Page::getInstance($rows[0]['pagesid']);

		// create instance and cache it

		$revision = self::createRevision($page, $rows[0]);
		self::$instancesById[$revision->id] = $revision;

		return $revision;

	}

    /**
     * instantiate all revisions of a page
     *
     * @param Page $page
     * @param Locale|null $locale
     * @return Revision[]
     * @throws ApplicationException
     */
	public static function getInstancesForPage(Page $page, Locale $locale = null): array
    {
        $pageId = $page->getId();

		if($pageId && isset(self::$instancesByPage[$pageId])) {
			return self::$instancesByPage[$pageId];
		}

		// get all revisions

		if(is_null($locale)) {
			$rows = Application::getInstance()->getVxPDO()->doPreparedQuery("
				SELECT
					*
				FROM
					revisions
				WHERE
					pagesID = ?", [$pageId]
			);
		}
		
		// get revisions for specified locale

		else {
			$rows = Application::getInstance()->getVxPDO()->doPreparedQuery("
				SELECT
					*
				FROM
					revisions
				WHERE
					locale = ? AND
					pagesID = ?", [$locale->getLocaleId(), $pageId]
			);
		}

		$instances = [];
		
		// generate and store instance

		foreach($rows as $row) {
			
			if(isset(self::$instancesById[$row['revisionsid']])) {
				$revision = self::$instancesById[$row['revisionsid']];
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
     * @throws \Exception
     */
	private static function createRevision (Page $page, array $data): self
    {
		$data = array_change_key_case($data, CASE_LOWER);
		
		$revision = new self($page);

		$revision->id = (int) $data['revisionsid'];
		$revision->active = (bool) $data['active'];
		$revision->authorId = $data['authorid'] ?: null;

		$revision->firstCreated = $data['firstcreated'] ? new \DateTime($data['firstcreated']) : null;
		$revision->lastUpdated = $data['lastupdated'] ? new \DateTime($data['lastupdated']) : null;

		if(!is_null($data['locale'])) {
			$revision->locale = new Locale($data['locale']);
		}

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
	public function __construct(Page $page)
    {
		$this->page = $page;
	}
	
	/**
	 * clone revision
	 * unset id, to allow subsequent saving
	 */
	public function __clone()
    {
		$this->id = null;
		$this->lastUpdated = null;
		$this->firstCreated = null;

		$this->updateHash();
	}
	
	/**
	 * reset active flag
	 * 
	 * make change persistent, if revision is already saved
	 * a previously active revision is automatically deactivated
	 */
	public function activate(): self
    {
		$currentlyActive = $this->page->getActiveRevision();

		if($this !== $currentlyActive) {

			if($currentlyActive) {
				$currentlyActive->deActivate();
			}

			$this->active = true;
			
			if($this->id) {
				Application::getInstance()->getVxPDO()->updateRecord(
					'revisions',
					$this->id,
					[
						'active' => 1
					]
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
	public function deactivate(): self
    {
		$this->active = false;

		if($this->id) {
			
			Application::getInstance()->getVxPDO()->updateRecord(
				'revisions',
				$this->id,
				['active' => null]
			);
		}
		
		return $this;
	}

    /**
     * make changes persistent
     * saving of a revision happens only once
     * further updates are done by inserting new revisions
     *
     * @return Revision
     * @throws PageException
     * @throws ApplicationException
     */
	public function save(): self
    {
		if(isset($this->id)) {
			throw new PageException('Cannot save a previously saved revision.');
		}

		if(is_null($this->firstCreated)) {
			$this->firstCreated = new \DateTime();
		}

		$toSave = [];

		foreach($this->monitoredAttributes as $attr) {
			$toSave[$attr] = $this->$attr;
		}

		// save by inserting new revision record

		$this->id = (int) Application::getInstance()->getVxPDO()->insertRecord('revisions', array_merge(
			$toSave,
			[
				'pagesID'		=> $this->page->getId(),
				'authorID'		=> $this->authorId,
				'active'		=> (int) $this->active,
				'firstCreated'	=> $this->firstCreated->format('Y-m-d H:i:s')
			]
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
	public function delete(): void
    {
		if($this->id) {
			Application::getInstance()->getVxPDO()->deleteRecord('revisions', $this->id);
			unset(self::$instancesById[$this->id]);
		}

		if(isset(self::$instancesByPage[$this->page->getId()])) {
			foreach(self::$instancesByPage[$this->page->getId()] as $ndx => $revision) {
				if($revision === $this) {
					array_splice(self::$instancesByPage[$this->page->getId()], $ndx, 1);
					break;
				}
			}
		} 
	}

	/**
	 * get primary key of revision
	 * @return null|integer
	 */
	public function getId(): ?int
    {
		return $this->id;
	}

	/**
	 * get "parent" page of revision
	 * @return Page
	 */
	public function getPage(): Page
    {
		return $this->page;
	}

	/**
	 * get author id of revision
	 * 
	 * @return null|integer
	 */
	public function getAuthorId(): ?int
    {
		return $this->authorId;
	}

	/**
	 * set author id
	 * 
	 * @param integer $authorId
	 * @return Revision
	 */
	public function setAuthorId(int $authorId): self
    {
		$this->authorId = $authorId;
		return $this;
	}

	/**
	 * get stored timestamp of last update
	 * this timestamp is set by DB wrapper or db itself
     *
	 * @return null|\DateTime
	 */
	public function getLastUpdated(): ?\DateTime
    {
		return $this->lastUpdated;
	}

	/**
	 * get stored timestamp of record creation
	 * this timestamp is set by DB wrapper upon saving, if not set explicitly
	 * @return null|\DateTime
	 */
	public function getFirstCreated(): ?\DateTime
    {
		return $this->firstCreated;
	}

	/**
	 * set timestamp of record creation
	 * required to synchronize timestamps with imported template files
     *
     * @param \DateTime $firstCreated
	 * @return Revision
	 */
	public function setFirstCreated(\DateTime $firstCreated): self
    {
		$this->firstCreated = $firstCreated;
		return $this;
	}

	/**
	 * clear timestamp of record creation
	 * so that the DB wrapper can set the attribute value
	 * @return Revision
	 */
	public function clearFirstCreated(): self
    {
        $this->firstCreated = null;
		return $this;
	}

	/**
	 * get active status of revision
	 * @return null|boolean
	 */
	public function isActive(): ?bool
    {
		return $this->active;
	}

	/**
	 * activate or deactivate revision
	 * @param boolean $active
	 * @return Revision
	 */
	public function setActive(bool $active): self {
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
	public function containsPHP(): bool
    {
		if(is_null($this->containsPHP)) {
			$this->setContainsPHP();
		}

		return $this->containsPHP;
	}

    /**
     * get title
     * @param string|null $default
     * @return null|string
     */
	public function getTitle(string $default = null): ?string
    {
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
	public function setTitle(string $title): self
    {
		$this->title = $title;
		return $this;
	}

	/**
	 * get keywords
	 * @param string|null $default
	 * @return string|null
	 */
	public function getKeywords(string $default = null): ?string
    {
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
	public function setKeywords(string $keywords): self
    {
		$this->keywords = $keywords;
		return $this;
	}

    /**
     * get description
     * @param string|null $default
     * @return string|null
     */
	public function getDescription(string $default = null): ?string
    {
		if(!is_null($this->description)) {
			return $this->description;
		}
		return $default;
	}

	/**
	 * set description
	 * @param string $description
	 * @return Revision
	 */
	public function setDescription(string $description): self
    {
		$this->description = $description;
		return $this;
	}

	/**
	 * get locale
	 * @return Locale|null
	 */
	public function getLocale(): ?Locale
    {
		return $this->locale;
	}

	/**
	 * set locale
	 * @param Locale $locale
	 * @return Revision
	 */
	public function setLocale(Locale $locale): self
    {
		$this->locale = $locale;
		return $this;
	}

	/**
	 * get markup
	 * @return string|null
	 */
	public function getMarkup(): ?string
    {
		return $this->markup;
	}

	/**
	 * set markup
	 * @param string $markup
	 * @return Revision
	 */
	public function setMarkup(string $markup): self
    {
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
	public function wasChanged(): bool
    {
		$hash = '';
		
		foreach($this->monitoredAttributes as $attr) {
			$hash .= $this->$attr;
		}
		
		return $this->savedDataHash !== sha1($hash);
	}

	/**
	 * calculate hash to detect changes to monitoredAttributes
	 */
	private function updateHash(): void
    {
		$hash = '';

		foreach($this->monitoredAttributes as $attr) {
			$hash .= $this->$attr;
		}
		
		$this->savedDataHash = sha1($hash);
	}
	
	/**
	 * check markup whether it contains <? or <?php and an optional closing tag
	 */
	private function setContainsPHP(): void
    {
		$this->containsPHP = preg_match('~\\<\\?(\\s+|php).*?(\\?\\>)~is', $this->markup);
	}
}