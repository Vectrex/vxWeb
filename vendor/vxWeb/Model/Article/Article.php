<?php
/*
 * This file is part of the vxPHP/vxWeb framework
 *
 * (c) Gregor Kofler <info@gregorkofler.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vxWeb\Model\Article;

use vxPHP\Application\Exception\ApplicationException;
use vxPHP\File\Exception\FilesystemFileException;
use vxPHP\File\Exception\FilesystemFolderException;
use vxWeb\Model\Article\Exception\ArticleException;
use vxWeb\Model\ArticleCategory\ArticleCategory;
use vxWeb\Model\ArticleCategory\Exception\ArticleCategoryException;
use vxWeb\Model\MetaFile\Exception\MetaFileException;
use vxWeb\Model\MetaFile\Exception\MetaFolderException;
use vxWeb\Model\MetaFile\MetaFile;

use vxPHP\Application\Application;
use vxPHP\Observer\PublisherInterface;
use vxPHP\Database\Util;

/**
 * Mapper class for articles, stored in table articles
 *
 * @author Gregor Kofler
 * @version 1.1.0 2020-09-12
 */

class Article implements PublisherInterface {

	/**
	 * cached instances identified by their id
	 * 
	 * @var Article[]
	 */
	private static	$instancesById;

	/**
	 * cached instances identified by their alias
	 * 
	 * @var Article[]
	 */
	private static	$instancesByAlias;

	/**
	 * primary key
	 * 
	 * @var integer
	 */
	private	$id;
	
	/**
	 * unique alias
	 * 
	 * @var string
	 */
	private	$alias;
			
	/**
	 * headline of article
	 * 
	 * @var string
	 */
	private $headline;
			
	/**
	 * "other" data of article
	 * 
	 * @var array
	 */
	private	$data;
			
	/**
	 * arbitrary flags
	 * controllers can decide how to interpret them
	 * 
	 * @var integer
	 */
	private	$customFlags;
			
	/**
	 * numeric indicator that can be used by
	 * controllers to enhance or override other sorting rules
	 * 
	 * @var integer
	 */
	private	$customSort;

	/**
	 * flags an article as published
	 * 
	 * @var bool
	 */
	private	$published;
	
	/**
	 * all files linked to this article
     * array items contain both the metafile reference and additional
     * relation specific information (e.g. visibility)
	 * 
	 * @var array
	 */
	private	$linkedFiles;
			
	/**
	 * flag which indicates that linked files need to be updated
	 * 
	 * @var boolean
	 */
	private	$updateLinkedFiles;
			
	/**
	 * stores previously saved attribute values of article
	 * allows verification whether changes of article have occured
	 * 
	 * @var \StdClass
	 */
	private	$previouslySavedValues;

	/**
	 * colunms which can be retrieved with the getData() method
	 *  
	 * @var array
	 */
	private	$dataCols = ['teaser', 'content', 'subline'];

	/**
	 * the category the article belongs to
	 * 
	 * @var ArticleCategory
	 */
	private	$category;

	/**
	 * the article date
	 * 
	 * @var \DateTime
	 */
	private	$articleDate;

	/**
	 * optional date information which can be used by controllers 
	 * 
	 * @var \DateTime
	 */
	private	$displayFrom;

	/**
	 * optional date information which can be used by controllers 
	 * 
	 * @var \DateTime
	 */
	private	$displayUntil;

	/**
	 * timestamp of last update of article 
	 * 
	 * @var \DateTime
	 */
	private	$lastUpdated;

	/**
	 * timestamp of article creation
	 *  
	 * @var \DateTime
	 */
	private	$firstCreated;

	/**
	 * the id of the user which created the article
	 * 
	 * @var integer
	 */
	private	$createdById;

	/**
	 * the id of the user which caused the last update
	 * 
	 * @var integer
	 */
	private	$updatedById;

	/**
	 * the id of the user which published (or unpublished) the article
	 *  
	 * @var int
	 */
	private	$publishedById;

	/**
	 * these attributes do not indicate
	 * a change of the record despite differing
	 * from current value
	 * 
	 * @var array
	 */
	private	$notIndicatingChange = [
		'published', 'publishedById'
	];
	
	/**
	 * property names which are set to NULL when cloning the object
	 * 
	 * @var array
	 */
	private $propertiesToReset = [
		'updatedById',
		'id',
		'alias'
	];
	
	public function __construct() {
	}
	
	public function __clone() {
		
		// link files, when necessary
		
		if(is_null($this->linkedFiles)) {
		    $this->readFilesForArticle();
		}

		// reset certain properties; forces insertion when saving
		
		foreach($this->propertiesToReset as $property) {
			$this->$property = null;
		}

		// make sure that linked files are kept and saved

		$this->updateLinkedFiles = true;
		
		// create new headline and therefore new alias

		$this->headline = 'Copy: ' . $this->headline; 

	}

	public function __toString() {

		return $this->alias;

	}

	/**
	 * checks whether an article was changed when compared to the data used for instancing
	 * evaluates to TRUE for a new article
	 * if $evaluateAll is TRUE, attributes in notIndicatingChange are still checked
	 *
	 * @todo changes of linked files are currently ignored
	 * 
	 * @param boolean $evaluateAll
	 *
	 * @return boolean
	 */
	public function wasChanged($evaluateAll = false): bool
    {
		if(is_null($this->previouslySavedValues)) {
			return true;
		}
		
		foreach(array_keys(get_object_vars($this->previouslySavedValues)) as $p) {

			// some attributes might not indicate a change

			if(!$evaluateAll && in_array($p, $this->notIndicatingChange, true)) {
				continue;
			}

			if(is_array($this->previouslySavedValues->$p)) {
				if(count(array_diff_assoc($this->previouslySavedValues->$p, $this->$p)) > 0) {
					return true;
				}
			}
			else {

				// non type-strict comparison for DateTime instances

				if($this->previouslySavedValues->$p instanceof \DateTime) {

					if($this->previouslySavedValues->$p != $this->$p) {
						return true;
					}

				}
				else if($this->previouslySavedValues->$p !== $this->$p) {
                    return true;
                }
			}
		}

		return false;
	}

    /**
     * store new article in database or update changes to existing article
     *
     * @throws ArticleException
     * @throws ApplicationException
     * @todo consider transactions
     */
	public function save(): void
    {
		$db = Application::getInstance()->getDb();

		// a headline is a required attribute

		if(is_null($this->headline) || trim($this->headline) === '') {
			throw new ArticleException("Headline not set. Article can't be inserted", ArticleException::ARTICLE_HEADLINE_NOT_SET);
		}

		// a category is a required attribute
		
		if(is_null($this->category)) {
			throw new ArticleException("Category not set. Article can't be inserted", ArticleException::ARTICLE_CATEGORY_NOT_SET);
		}

		// allow listeners to react to event

		ArticleEvent::create(ArticleEvent::BEFORE_ARTICLE_SAVE, $this)->trigger();

		// afterwards collect all current data in array

		$cols = array_merge(
			(array) $this->getData(),
			[
				'alias' => $this->alias,
				'articlecategoriesid' => $this->category->getId(),
				'headline' => $this->headline,
				'article_date' => is_null($this->articleDate) ? null : $this->articleDate->format('Y-m-d H:i:s'),
				'display_from' => is_null($this->displayFrom) ? null : $this->displayFrom->format('Y-m-d H:i:s'),
				'display_until' => is_null($this->displayUntil) ? null : $this->displayUntil->format('Y-m-d H:i:s'),
				'published' => (int) $this->published ?: null,
				'customflags' => $this->customFlags,
				'customsort' => $this->customSort,
				'publishedby' => $this->publishedById ?: null,
				'updatedby' => $this->updatedById ?: null
			]
		);

		if(!is_null($this->id)) {

			// is a full update necessary?

			if($this->wasChanged()) {

				// update

				$this->alias = Util::getAlias($db, $this->headline, 'articles', $this->id);
				$cols['alias'] = $this->alias;

				$db->updateRecord('articles', $this->id, $cols);

			}

			// were attributes changed, which don't indicate an update (e.g. 'published')?

			else if($this->wasChanged(true)) {

				// update, but don't set lastUpdated and updatedBy

				unset($cols['updatedBy']);
				$db->ignoreLastUpdated();

				$this->alias = Util::getAlias($db, $this->headline, 'articles', $this->id);
				$cols['alias'] = $this->alias;
				$db->updateRecord('articles', $this->id, $cols);
				
				$db->updateLastUpdated();
			}
		}
		else {

			// insert

			$this->alias = Util::getAlias($db, $this->headline, 'articles');

			$cols = array_merge(
				(array) $this->getData(),
				[
					'alias' => $this->alias,
					'articlecategoriesid' => $this->category->getId(),
					'headline' => $this->headline,
					'article_date' => is_null($this->articleDate) ? null : $this->articleDate->format('Y-m-d H:i:s'),
					'display_from' => is_null($this->displayFrom) ? null : $this->displayFrom->format('Y-m-d H:i:s'),
					'display_until' => is_null($this->displayUntil) ? null : $this->displayUntil->format('Y-m-d H:i:s'),
					'published' => $this->published,
					'customflags' => $this->customFlags,
					'customsort' => $this->customSort,
					'publishedby' => $this->publishedById ?: null,
					'createdby' => $this->createdById ?: null
				]
			);

			$this->id = $db->insertRecord('articles', $cols);

		}
		
		// store link information for linked files if linked files were changed in any way

		if($this->updateLinkedFiles) {

			// delete all previous entries
	
			$db->deleteRecord('articles_files', ['articlesID' => $this->id]);

			// save new references and use position in array as customSort value

			$rows = [];

			foreach($this->linkedFiles as $ndx => $item) {
				$rows[] = [
					'articlesid' => $this->id,
					'filesid' => $item['file']->getId(),
					'customsort' => $ndx,
                    'hidden' => $item['rel']['hidden'] ? 1 : null
				];
			}

			$db->insertRecords('articles_files', $rows);

			$this->updateLinkedFiles = false;
		}

		ArticleEvent::create(ArticleEvent::AFTER_ARTICLE_SAVE, $this)->trigger();
	}

	/**
	 * delete article, unlink references in metafiles
	 * 
	 * @todo consider transactions
	 * 
	 */
	public function delete(): void
    {
		// only already saved articles can actively be deleted

		if(!is_null($this->id)) {

			ArticleEvent::create(ArticleEvent::BEFORE_ARTICLE_DELETE, $this)->trigger();
				
			// delete record

			$db = Application::getInstance()->getDb(); 
			$db->deleteRecord('articles', $this->id);

			// delete instance references

            unset(self::$instancesById[$this->id], self::$instancesByAlias[$this->alias]);

            $db->deleteRecord('articles_files', ['articlesid' => $this->id]);

			ArticleEvent::create(ArticleEvent::AFTER_ARTICLE_DELETE, $this)->trigger();
		}
	}

    /**
     * link a metafile to the article; additionally links article to metaFile
     * when $sortPosition is set, the file reference is moved to this position within the files array
     * a newly linked file is always visible
     *
     * @param MetaFile $file
     * @param int|null $sortPosition
     * @return self
     * @throws ApplicationException
     * @throws FilesystemFileException
     * @throws FilesystemFolderException
     * @throws MetaFileException
     * @throws MetaFolderException
     */
	public function linkMetaFile(MetaFile $file, int $sortPosition = null): self
    {
		// get all linked files if not done previously

		if(is_null($this->linkedFiles)) {
			$this->readFilesForArticle();
		}

		if(!in_array($file, array_column($this->linkedFiles, 'file'), true)) {

			// append file when no sort position is set or sort position beyond linked files length

			if(is_null($sortPosition) || !is_numeric($sortPosition) || (int) $sortPosition >= count($this->linkedFiles)) {
				$this->linkedFiles[] = ['file' => $file, 'rel' => ['hidden' => false]];
			}
			
			// otherwise insert reference at given position

			else {
				array_splice($this->linkedFiles, $sortPosition, 0, [['file' => $file, 'rel' => ['hidden' => false]]]);
			}

			$this->updateLinkedFiles = true;
		}

        return $this;
    }

    /**
     * remove a file reference
     * ensures proper re-ordering of files array
     *
     * @param MetaFile $file
     * @return Article
     * @throws ApplicationException
     * @throws FilesystemFileException
     * @throws FilesystemFolderException
     * @throws MetaFileException
     * @throws MetaFolderException
     */
	public function unlinkMetaFile(MetaFile $file): self
    {
		// get all linked files if not done previously

		if(is_null($this->linkedFiles)) {
		    $this->readFilesForArticle();
		}

		// remove file reference if file is linked and ensure continuous numeric indexes

		if(($pos = array_search($file, array_column($this->linkedFiles, 'file'), true)) !== false) {
			array_splice($this->linkedFiles, $pos, 1);

			$this->updateLinkedFiles = true;
		}

		return $this;
	}

    /**
     * set custom sort value $file
     *
     * @param MetaFile $file
     * @param int $sortPosition
     * @return Article
     * @throws ApplicationException
     * @throws FilesystemFileException
     * @throws FilesystemFolderException
     * @throws MetaFileException
     * @throws MetaFolderException
     */
	public function setCustomSortOfMetaFile(MetaFile $file, int $sortPosition): Article
    {
		// get all linked files if not done previously
		
		if(is_null($this->linkedFiles)) {
			$this->readFilesForArticle();
		}

		// is $file linked?

		if(($pos = array_search($file, array_column($this->linkedFiles, 'file'), true)) !== false) {
		
			// is $sortPosition valid and different from current position

			if(is_numeric($sortPosition) && (int) $sortPosition !== $pos) {

				// remove at old position

				$item = array_splice($this->linkedFiles, $pos, 1);
				
				// insert at new position
				
				array_splice($this->linkedFiles, $sortPosition, 0, $item);

				$this->updateLinkedFiles = true;
			}
		}
		
		return $this;
	}

	/**
	 * get numeric id (primary key in db) of article
	 *
	 * @return integer
	 */
	public function getId(): int
    {
		return $this->id;
	}

	/**
	 * get alias of article
	 *
	 * @return string
	 */
	public function getAlias(): string
    {
		return $this->alias;
	}

	/**
	 * set user id which created the article
	 * will only be effective with first save of article
	 * 
	 * @param integer $userId
	 * @return Article
	 */
	public function setCreatedById($userId): self
    {
		if(is_null($this->createdById)) {
			$this->createdById = (int) $userId ?: null;
		}
		return $this;
	}

	/**
	 * get user id which created article
	 *
	 * @return integer
	 */
	public function getCreatedById(): ?int
    {
		return $this->createdById;
	}

	/**
	 * set user id which (last) updated the article
	 *
	 * @param integer $userId
	 * @return Article
	 */
	public function setUpdatedById($userId): self
    {
		$this->updatedById = (int) $userId ?: null;
		return $this;
	}
	
	/**
	 * get id of user which (last) updated article
	 *
	 * @return integer
	 */
	public function getUpdatedById(): int
    {
		return $this->updatedById;
	}

	/**
	 * get id of user which (un)published article
	 *
	 * @return int
	 */
	public function getPublishedById(): ?int
    {
		return $this->publishedById;
	}

	/**
	 * get timestamp of article creation
	 *
	 *  @return \DateTime
	 */
	public function getFirstCreated(): ?\DateTime
    {
		return $this->firstCreated;
	}

	/**
	 * get timestamp of last article update
	 *
	 *  @return \DateTime
	 */
	public function getLastUpdated(): ?\DateTime
    {
		return $this->lastUpdated;
	}

	/**
	 * get custom sort value
	 *
	 * @return int
	 */
	public function getCustomSort(): ?int
    {
		return $this->customSort;
	}

	/**
	 * set custom sort value
	 *
	 * @param mixed $ndx
	 * @return self
	 */
	public function setCustomSort($ndx): self
    {
		if(is_numeric($ndx)) {
			$this->customSort = (int) $ndx;
		}
		else {
			$this->customSort = null;
		}
		return $this;
	}

    /**
     * get custom flags of article
     *
     * @return int
     */
    public function getCustomFlags(): ?int
    {
        return $this->customFlags;
    }

    /**
     * set custom flags of article
     *
     * @param int|null $customFlags
     * @return Article
     */
    public function setCustomFlags(int $customFlags = null): self
    {
        $this->customFlags = $customFlags;
        return $this;
    }

    /**
	 * get article date
	 *
	 * @return \DateTime
	 */
	public function getDate(): ?\DateTime
    {
		return $this->articleDate;
	}

    /**
     * set article date, omitting argument deletes date value
     *
     * @param \DateTime|null $articleDate
     * @return Article
     */
	public function setDate(\DateTime $articleDate = null): self
    {
		$this->articleDate = $articleDate;
		return $this;
	}

	/**
	 * get displayFrom date
	 *
	 * @return \DateTime
	 */
	public function getDisplayFrom(): ?\DateTime
    {
		return $this->displayFrom;
	}

    /**
     * set displayFrom date, omitting argument deletes date value
     *
     * @param \DateTime|null $displayFrom
     * @return Article
     */
	public function setDisplayFrom(\DateTime $displayFrom = null): self
    {
		$this->displayFrom = $displayFrom;
		return $this;
	}

	/**
	 * get displayUntil date
	 *
	 * @return \DateTime
	 */
	public function getDisplayUntil(): ?\DateTime
    {
		return $this->displayUntil;
	}

    /**
     * set displayUntil date, omitting argument deletes date value
     *
     * @param \DateTime|null $displayUntil
     * @return Article
     */
	public function setDisplayUntil(\DateTime $displayUntil = null): self
    {
		$this->displayUntil = $displayUntil;
		return $this;
	}

	/**
	 * assign article to category
	 *
	 * @param ArticleCategory $category
	 * @return Article
	 */
	
	public function setCategory(ArticleCategory $category): self
    {
		$this->category = $category;
		return $this;
	}

	/**
	 * get assigned category
	 *
	 * @return ArticleCategory
	 */
	public function getCategory(): ?ArticleCategory
    {
		return $this->category;
	}

    /**
     * set headline of article; this also dertermines the alias
     *
     * @param string $headline
     * @return Article
     */
	public function setHeadline(string $headline): self
    {
		$this->headline = trim($headline);
		return $this;
	}

	/**
	 * get headline
	 *
	 * @return string
	 */
	public function getHeadline(): string
    {
		return $this->headline;
	}

    /**
     * get misc article data
     * when $ndx is omitted all misc data is returned in an associative array
     *
     * @param string|null $ndx
     * @return mixed
     */
	public function getData(string $ndx = null)
    {
		if(is_null($ndx)) {
			return $this->data;
		}

		$ndx = strtolower($ndx);

        return $this->data[$ndx] ?? null;
	}

	/**
	 * sets misc data of article; only keys listet in Article::dataCols are accepted
	 *
	 * @param array $data
	 * @return self
	 */
	public function setData(array $data): self
    {
		$data = array_change_key_case($data, CASE_LOWER);

		foreach($this->dataCols as $c) {
			if(isset($data[$c])) {
				$this->data[$c] = $data[$c];
			}
		}
		
		return $this;
	}

    /**
     * returns array of MetaFile instances linked to the article
     *
     * @param bool $includeHidden
     * @return MetaFile[]
     * @throws ApplicationException
     * @throws FilesystemFileException
     * @throws FilesystemFolderException
     * @throws MetaFileException
     * @throws MetaFolderException
     */
	public function getLinkedMetaFiles(bool $includeHidden = false): array
    {
		if(!is_null($this->id) && is_null($this->linkedFiles)) {
            $this->readFilesForArticle();
		}
		if ($includeHidden) {
            return array_column($this->linkedFiles, 'file');
        }
		return array_column(array_filter($this->linkedFiles, static function($item) {
		    return empty($item['rel']['hidden']);
		}), 'file');
	}

    /**
     * get the visibility of a linked file
     * returns true when file is visible, false when file is hidden
     *
     * @param MetaFile $file
     * @return bool
     * @throws ArticleException
     */
	public function getLinkedFileVisibility (MetaFile $file): bool
    {
        if (($ndx = array_search($file, array_column($this->linkedFiles, 'file'), true)) === false ) {
            throw new ArticleException("File '%s' not linked to article.", $file->getFilename());
        }

        return !$this->linkedFiles[$ndx]['rel']['hidden'];
    }

    /**
     * set the visibilty of a linked file
     *
     * @param MetaFile $file
     * @param bool $visibility
     * @return $this
     * @throws ArticleException
     */
    public function setLinkedFileVisibility (MetaFile $file, bool $visibility): self
    {
        if (($ndx = array_search($file, array_column($this->linkedFiles, 'file'), true)) === false ) {
            throw new ArticleException("File '%s' not linked to article.", $file->getFilename());
        }

        if ($this->linkedFiles[$ndx]['rel']['hidden'] === $visibility) {
            $this->linkedFiles[$ndx]['rel']['hidden'] = !$visibility;
            $this->updateLinkedFiles = true;
        }

        return $this;
    }

    /**
     * returns array of MetaFile instances with web image mimetype linked to the article
     *
     * @param bool $includeHidden
     * @return MetaFile[]
     * @throws ApplicationException
     * @throws FilesystemFileException
     * @throws FilesystemFolderException
     * @throws MetaFileException
     * @throws MetaFolderException
     */
	public function getLinkedWebImages(bool $includeHidden = false): array
    {
        if(!is_null($this->id) && is_null($this->linkedFiles)) {
            $this->readFilesForArticle();
        }

        // mimetype relies on database entry to speed up execution

        if ($includeHidden) {
            return array_column(array_filter($this->linkedFiles, static function ($item) {
                return $item['file']->isWebImage();
            }), 'file');
        }
        return array_column(array_filter($this->linkedFiles, static function($item) {
            return empty($item['rel']['hidden']) && $item['file']->isWebImage();
        }), 'file');
    }

    /**
     * set 'published' attribute and store user id
     *
     * @param int|null $userId
     * @return self
     */
	public function publish(int $userId = null): Article
    {
		$this->publishedById = (int) $userId ?: null;
		$this->published = true;

		return $this;
	}

    /**
     * unset 'published' attribute and store user id
     *
     * @param int|null $userId
     * @return self
     */
	public function unpublish(int $userId = null): Article
    {
		$this->publishedById = (int) $userId ?: null;
		$this->published = false;

		return $this;
	}

	/**
	 * get state of published flag
	 *
	 * @return boolean
	 */
	public function isPublished(): bool
    {
		return (boolean) $this->published;
	}

    /**
     * create Article instance from data supplied in $articleData
     *
     * @param array $articleData
     * @return Article
     * @throws ArticleCategoryException
     */
	private static function createInstance(array $articleData): Article
    {
		$article = new self();
		
		// ensure lower case keys
		
		$articleData = array_change_key_case($articleData, CASE_LOWER);

		// set identification

		$article->alias = $articleData['alias'];
		$article->id = $articleData['articlesid'];

		// set category

		$article->category = ArticleCategory::getInstance($articleData['articlecategoriesid']);

		// set user id's
		
		$article->createdById = $articleData['createdby'];
		$article->updatedById = $articleData['updatedby'];
		$article->publishedById = $articleData['publishedby'];

		// set date information

		if(!empty($articleData['display_from'])) {
			$article->displayFrom = new \DateTime($articleData['display_from']);
		}

		if(!empty($articleData['display_until'])) {
			$article->displayUntil = new \DateTime($articleData['display_until']);
		}

		if(!empty($articleData['article_date'])) {
			$article->articleDate = new \DateTime($articleData['article_date']);
		}

		if(!empty($articleData['firstcreated'])) {
			$article->firstCreated = new \DateTime($articleData['firstcreated']);
		}

		if(!empty($articleData['lastupdated'])) {
			$article->lastUpdated = new \DateTime($articleData['lastupdated']);
		}

		// flags and sort

		$article->published = $articleData['published'];
		$article->customFlags = $articleData['customflags'];
		$article->customSort = $articleData['customsort'];

		// set various text fields

		$article->setHeadline($articleData['headline']);
		$article->setData($articleData);

		// backup values to check whether record was changed

		$article->previouslySavedValues = new \stdClass();

		$article->previouslySavedValues->headline = $article->headline;
		$article->previouslySavedValues->category = $article->category;
		$article->previouslySavedValues->data = $article->data;
		$article->previouslySavedValues->displayFrom = $article->displayFrom;
		$article->previouslySavedValues->displayUntil = $article->displayUntil;
		$article->previouslySavedValues->articleDate = $article->articleDate;
		$article->previouslySavedValues->published = $article->published;
		$article->previouslySavedValues->customFlags = $article->customFlags;
		$article->previouslySavedValues->customSort = $article->customSort;
		
		return $article;
	}

    /**
     * returns article instance identified by numeric id or alias
     *
     * @param mixed $id
     * @return self
     * @throws ArticleException
     * @throws ApplicationException
     * @throws ArticleCategoryException
     */
	public static function getInstance($id): Article
    {
		$db = Application::getInstance()->getDb();

		if(is_numeric($id)) {
			$id = (int) $id;
			if(isset(self::$instancesById[$id])) {
				return self::$instancesById[$id];
			}

			$col = 'articlesid';
		}
		else {
			if(isset(self::$instancesByAlias[$id])) {
				return self::$instancesByAlias[$id];
			}

			$col = 'alias';
		}

		$rows = $db->doPreparedQuery(
			sprintf("
				SELECT
					a.*
				FROM
					articles a
				WHERE
					a.%s = ?",
				$col
			),
			[$id]
		);

		if(!count($rows)) {
			throw new ArticleException(sprintf("Article with %s '%s' does not exist.", $col, $id), ArticleException::ARTICLE_DOES_NOT_EXIST);
		}

		// generate and store instance

		$article = self::createInstance($rows[0]);

		self::$instancesByAlias[$article->alias] = $article;
		self::$instancesById[$article->id] = $article;

		return $article;
	}

    /**
     * returns array of Article objects identified by numeric id or alias
     * when $ids is not set, all available articles are instantiated
     *
     * @param array $ids contains mixed article ids or alias
     * @return array
     * @throws ArticleException
     * @throws ApplicationException
     * @throws ArticleCategoryException
     */
	public static function getInstances(array $ids = null) {

		$db = Application::getInstance()->getDb();

		$articles = [];

		// get all articles

		if(is_null($ids)) {

			foreach($db->doPreparedQuery('SELECT a.* FROM articles a') as $row) {

				if(!isset(self::$instancesById[$row['articlesid']])) {

					$article = self::createInstance($row);

					self::$instancesByAlias[$article->alias]	= $article;
					self::$instancesById[$article->id]			= $article;
						
				}

				$articles[] = self::$instancesById[$row['articlesid']];
			}
		}

		else {

			$toRetrieveById		= [];
			$toRetrieveByAlias	= [];
	
			foreach($ids as $id) {
	
				if(is_numeric($id)) {
					$id = (int) $id;
	
					if(!isset(self::$instancesById[$id])) {
						$toRetrieveById[] = $id;
					}
				}
	
				else {
					if(!isset(self::$instancesByAlias[$id])) {
						$toRetrieveByAlias[] = $id;
					}
				}

			}
	
			$where = array();

			if(count($toRetrieveById)) {
				$where[] = 'a.articlesid IN (' . implode(',', array_fill(0, count($toRetrieveById), '?')). ')';
			}
			if(count($toRetrieveByAlias)) {
				$where[] = 'a.alias IN (' . implode(',', array_fill(0, count($toRetrieveByAlias), '?')). ')';
			}

			if(count($where)) {
				$rows = $db->doPreparedQuery('
					SELECT
						a.*
					FROM
						articles a
					WHERE
						' . implode(' OR ', $where),
				array_merge($toRetrieveById, $toRetrieveByAlias));

				foreach($rows as $row) {
					$article = self::createInstance($row);
					self::$instancesByAlias[$article->alias]	= $article;
					self::$instancesById[$article->id]			= $article;
				}
			}

			foreach($ids as $id) {
				$articles[] = self::getInstance($id);
			}
		}

		return $articles;

	}

    /**
     * get all articles assigned to given $category
     *
     * @param ArticleCategory $category
     * @return array
     * @throws ApplicationException
     * @throws ArticleCategoryException
     */
	public static function getArticlesForCategory(ArticleCategory $category) {

		$articles = [];

		$rows = Application::getInstance()->getDb()->doPreparedQuery('SELECT * FROM articles WHERE articlecategoriesID = ?', array($category->getId()));

		foreach($rows as $r) {
			if(!isset(self::$instancesById[$r['articlesid']])) {

				// create Article instance if it does not yet exist

				$article = self::createInstance($r);

				self::$instancesByAlias[$article->alias]	= $article;
				self::$instancesById[$article->id]			= $article;
			}

			$articles[] = self::$instancesById[$r['articlesid']];
		}

		return $articles;
	}

    /**
     * return all metafile instances linked to an article
     * with additional information stored in the relation
     *
     * @return void
     * @throws ApplicationException
     * @throws FilesystemFileException
     * @throws FilesystemFolderException
     * @throws MetaFileException
     * @throws MetaFolderException
     */
    private function readFilesForArticle(): void
    {
        $result = [];

        $rows = Application::getInstance()->getDb()->doPreparedQuery("
			SELECT
				f.filesid,
				af.hidden
			FROM
				files f
				INNER JOIN articles_files af ON af.filesid = f.filesid
			WHERE
				af.articlesid = ?
            ORDER BY af.customsort
			", [$this->getId()]);

        // fill metafile instances cache

        MetaFile::getInstancesByIds(array_column((array) $rows,'id'));

        foreach($rows as $row) {
            $result[] = [
                'file' => MetaFile::getInstance(null, $row['filesid']),
                'rel' => [
                    'hidden' => (boolean) $row['hidden']
                ]
            ];
        }

        $this->linkedFiles = $result;
    }
}
