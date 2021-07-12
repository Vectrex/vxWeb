<?php

namespace vxWeb\Model\Page;

use vxPHP\Application\Application;
use vxPHP\Application\Exception\ApplicationException;
use vxPHP\Application\Locale\Locale;
use vxPHP\Observer\EventDispatcher;
use vxPHP\Observer\PublisherInterface;
use vxPHP\Observer\GenericEvent;

/**
 * Mapper class to handle revisioned pages, stored in table `pages`
 *
 * @author Gregor Kofler
 * @version 0.4.1 2021-07-12
 * 
 * @todo creation of new pages (several setters are superfluous ATM)
 */
class Page implements PublisherInterface
{
	/**
	 * map of page instances indexed by their primary key
	 * 
	 * @var Page[]
	 */
	private static $instancesById = [];

	/**
	 * map of page instances indexed by their alias
	 * 
	 * @var Page[]
	 */
	private static $instancesByAlias = [];

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
	public function __construct()
    {
	}

    /**
     * retrieve page instance identified by either its id (numeric) or alias (string)
     *
     * @param string|int $id
     * @return Page
     * @throws PageException
     * @throws ApplicationException
     */
	public static function getInstance($id): self
    {
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

		$rows =  Application::getInstance()->getVxPDO()->doPreparedQuery("
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

		self::$instancesByAlias [$page->alias] = $page;
		self::$instancesById [$page->id] = $page;

		return $page;
	}

    /**
     * retrieve all currently stored pages
     *
     * @return Page[]
     * @throws ApplicationException
     */
	public static function getInstances(): array
    {
		foreach(Application::getInstance()->getVxPDO()->doPreparedQuery("
			SELECT
				*
			FROM
				pages
		", []) as $row) {

			if(!isset(self::$instancesById[(int) $row['pagesid']])) {

				$page = self::createInstance($row);

				self::$instancesByAlias [$page->alias] = $page;
				self::$instancesById [$page->id] = $page;
			}
		}

		return self::$instancesById;
	}

    /**
     * create page and set all attributes stored in $data
     *
     * @param array $data
     * @return Page
     * @throws \Exception
     */
	private static function createInstance(array $data): self
    {
		$page = new self();

		// set identification
		
		$page->id = (int) $data['pagesid'];
		$page->alias = $data['alias'];

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
     * delete a page, all its revisions and the exported file
     *
     * @throws ApplicationException|PageException
     */
	public function delete (): void
    {
        EventDispatcher::getInstance()->dispatch(new GenericEvent('beforePageDelete', $this));

        $this->deleteExport ();
        $db = Application::getInstance()->getVxPDO();
        $db->beginTransaction();
        Revision::purge ($this);
        $db->deleteRecord ('pages', $this->getId());
        $db->commit();

        unset (self::$instancesById[$this->getId()], self::$instancesByAlias[$this->getAlias()]);

        EventDispatcher::getInstance()->dispatch(new GenericEvent('afterPageDelete', $this));
    }

    /**
     * get all revisions
     *
     * @return Revision[]
     * @throws ApplicationException
     */
	public function getRevisions(): array
    {
		return Revision::getInstancesForPage($this);
	}

    /**
     * get active revision
     * returns NULL when no active revision is found
     *
     * @return null|Revision
     * @throws ApplicationException
     */
	public function getActiveRevision(): ?Revision
    {
		$revisions = $this->getRevisions();
		
		// proceed when revisions were found at all
		
		if($revisions) {
			foreach($revisions as $revision)  {

				if($revision->isActive()) {
					return $revision;
				}
			}
		}

		return null;
	}

    /**
     * sort revisions and return revision with latest creation date
     * @return null|Revision
     *
     * @throws ApplicationException
     * @todo do sorting only once
     */
	public function getNewestRevision(): ?Revision
    {
		$revisions = $this->getRevisions();

		// proceed when revisions were found at all

		if($revisions) {

			usort($revisions, static function (Revision $a, Revision $b) {
                $tsa = $a->getFirstCreated()->format('U');
				$tsb = $b->getFirstCreated()->format('U');
				if($tsa === $tsb) {
					return 0;
				}
				
				// sort descending
	
				return $tsa < $tsb ? 1 : -1;
			});
			
			return $revisions[0];
		}

		return null;
	}

    /**
     * sort revisions and return revision with earliest creation date
     * @return null|Revision
     *
     * @throws ApplicationException
     * @todo do sorting only once
     */
	public function getOldestRevision(): ?Revision
    {
		$revisions = $this->getRevisions();

		// proceed when revisions were found at all

		if($revisions) {

			usort($revisions, static function (Revision $a, Revision $b) {
				$tsa = $a->getFirstCreated()->format('U');
				$tsb = $b->getFirstCreated()->format('U');
				if($tsa === $tsb) {
					return 0;
				}
					
				// sort ascending
			
				return $tsa > $tsb ? 1 : -1;
			});

			return $revisions[0];
		}

		return null;
	}

    /**
     * retrieve a revision identified by a creation timestamp
     * @param \DateTime $dateTime
     * @return Revision|null
     * @throws ApplicationException
     */
	public function getRevisionByDateTime(\DateTime $dateTime): ?Revision
    {
		foreach($this->getRevisions() as $revision) {
			if($revision->getFirstCreated() && $revision->getFirstCreated()->format(DATE_W3C) === $dateTime->format(DATE_W3C)) {
				return $revision;
			}
		}
		return null;
	}

	public function deleteExport (): void
    {
        $app = Application::getInstance();
        $config	= $app->getConfig();

        if(is_null($config->paths['editable_tpl_path'])) {
            throw new PageException('No export path for templates defined.');
        }
        $revision = $this->getActiveRevision();

        if(is_null($revision)) {
            throw new PageException(sprintf("No active revision for template '%s' found.", $this->getAlias()));
        }

        @unlink($this->getExportPath($revision->getLocale()));
    }

    /**
     * exports the active revision of the page to its template file
     * path information is retrieved from the config object
     * the modification timestamp of the generated file is set to creation timestamp of the active revision
     *
     * @throws PageException
     * @throws ApplicationException
     */
	public function exportActiveRevision(): void
    {
		// dispatch 'beforePageRevisionExport' event to inform optional listeners

		EventDispatcher::getInstance()->dispatch(new GenericEvent('beforePageRevisionExport', $this));

        $revision = $this->getActiveRevision();

        if(is_null($revision)) {
            throw new PageException(sprintf("No active revision for template '%s' found.", $this->getAlias()));
        }

        $path = $this->getExportPath($revision->getLocale());

		if(!($handle = fopen($path, 'wb'))) {
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
     * get full path of template file
     *
     * @param Locale|null $locale
     * @return string
     * @throws ApplicationException
     * @throws PageException
     */
	private function getExportPath (Locale $locale = null): string
    {
        $app = Application::getInstance();
        $config	= $app->getConfig();

        if(is_null($config->paths['editable_tpl_path'])) {
            throw new PageException('No export path for templates defined.');
        }

        return
            rtrim($app->getRootPath(), DIRECTORY_SEPARATOR) .
            $config->paths['editable_tpl_path']['subdir'] .
            ($locale ? $locale->getLocaleId() . DIRECTORY_SEPARATOR : '') .
            $this->getTemplate();
    }

	/**
	 * get id
	 * @return null|integer
	 */
	public function getId(): ?int
    {
		return $this->id;
	}
	
	/**
	 * get alias
	 * @return null|string
	 */
	public function getAlias(): ?string
    {
		return $this->alias;
	}
	
	/**
	 * get title
	 * @return string
	 */
	public function getTitle(): ?string
    {
		return $this->title;
	}

	/**
	 * set title
	 * @param string|null $title
	 * @return Page
	 */
	public function setTitle(?string $title): self
    {
		$this->title = $title;
		return $this;
	}
	
	/**
	 * get keywords
	 * @return null|string
	 */
	public function getKeywords(): ?string
    {
		return $this->keywords;
	}

    /**
     * set keywords
     * @param string|null $keywords
     * @return Page
     */
	public function setKeywords(?string $keywords): self
    {
		$this->keywords = $keywords;
		return $this;
	}

	/**
	 * get template filename
	 * @return null|string
	 */
	public function getTemplate(): ?string
    {
		return $this->template;
	}
	
	/**
	 * set template filename
	 * @param string $template
	 * @return Page
	 */
	public function setTemplate(string $template): self
    {
		$this->template = $template;
		return $this;
	}
}