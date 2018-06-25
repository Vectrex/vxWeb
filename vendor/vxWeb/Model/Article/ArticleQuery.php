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

use vxPHP\Orm\Query;
use vxWeb\Model\Article\Article;
use vxWeb\Model\ArticleCategory\ArticleCategory;

use vxPHP\Database\DatabaseInterface;

/**
 * query object which returns an array of Article objects
 *
 * @example
 *
 * $articles =	vxPHP\Orm\Custom\ArticleQuery::create($db)->
 * 				filterByCategory($myCat)->
 * 				filterPublished()->
 * 				where('article_date < ?', new DateTime()->format('Y-m-d'))->
 * 				sortBy('customSort', FALSE)->
 * 				sortBy('Headline')->
 * 				selectFirst(2);
 *
 * @author Gregor Kofler
 * @version 0.6.0 2018-06-25
 */
class ArticleQuery extends Query {

	/**
	 * provide initial database connection
	 *
	 * @param DatabaseInterface $dbConnection
	 */
	public function __construct(DatabaseInterface $dbConnection) {

		$this->table		= 'articles';
		$this->alias		= 'a';
		$this->columns		= ['a.articlesid'];

		parent::__construct($dbConnection);

	}

	/**
	 * add WHERE clause that filters for $category
	 *
	 * @param ArticleCategory $category
	 * 
	 * @return self
	 */
	public function filterByCategory(ArticleCategory $category) {

		$this->addCondition("a.articlecategoriesid = ?", $category->getId());
		return $this;

	}

    /**
     * add WHERE clause which finds articles which are matched by
     * bit mask
     *
     * @param int $mask
     * @return $this
     */
	public function filterByCustomFlags($mask) {

	    $this->addCondition("a.customflags & ?", $mask);
	    return $this;

    }

	/**
	 * add WHERE clause that filters for several categories
	 *
	 * @param ArticleCategory[] $categories
	 * @return self
	 * @throws \InvalidArgumentException
	 */
	public function filterByCategories(array $categories) {
		
		$ids = [];

		foreach($categories as $category) {
			if(!$category instanceof ArticleCategory) {
				throw new \InvalidArgumentException(sprintf("Expected ArticleCategory, found %s.", gettype($category)));
			}
			
			$ids[] = $category->getId();
		}

		$this->addCondition('a.articlecategoriesid', $ids, 'IN');

		return $this;
		
	}

	/**
	 * add WHERE clause that removes all unpublished articles from resultset
	 * 
	 * @return self
	 */
	public function filterPublished() {

		$this->addCondition("a.published = 1");
		return $this;

	}

	/**
	 * add WHERE clause that removes all published articles from resultset
	 * 
	 * @return self
	 */
	public function filterUnPublished() {

		$this->addCondition("(a.published = 0 OR a.published IS NULL)");
		return $this;

	}

	/**
	 * add WHERE clause that filters for article category aliases
	 * 
	 * @param array $categoryNames
	 * 
	 * @return self
	 */
	public function filterByCategoryNames(array $categoryNames) {

		$this->innerJoin('articlecategories c', 'c.articlecategoriesid = a.articlecategoriesid');
		$this->addCondition('c.alias', $categoryNames, 'IN');
		return $this;

	}
	
	/**
	 * add WHERE clause that returns articles where $date is between Article::displayFrom and Article::displayUntil
	 * $date defaults to current date
	 * 
	 * @param \DateTime $date
	 * 
	 * @return self
	 */
	public function filterByDisplayFromToUntil(\DateTime $date = NULL) {

		if(!$date) {
			$date = new \DateTime();
		}
		
		$this->addCondition("a.display_from IS NULL OR a.display_from <= ?", $date->format('Y-m-d'));
		$this->addCondition("a.display_until IS NULL OR a.display_until >= ?", $date->format('Y-m-d'));

		return $this;

	}

    /**
     * executes query and returns array of Article instances
     *
     * @see \vxPHP\Orm\Query::select()
     * @return Article[]
     * @throws Exception\ArticleException
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws \vxWeb\Model\ArticleCategory\Exception\ArticleCategoryException
     */
	public function select() {

		$this->buildQueryString();
		$this->buildValuesArray();

		$ids = [];

		foreach($this->executeQuery() as $row) {
			$ids[] = $row['articlesid'];
		}

		return Article::getInstances($ids);
	}

    /**
     * adds LIMIT clause, executes query and returns array of Article instances
     *
     * @see \vxPHP\Orm\Query::selectFirst()
     * @param int $rows
     * @return Article[]
     * @throws Exception\ArticleException
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws \vxWeb\Model\ArticleCategory\Exception\ArticleCategoryException
     */
	public function selectFirst($rows = 1) {

		if(empty($this->columnSorts)) {
			throw new \RangeException("'" . __METHOD__ . "' requires a SORT criteria.");
		}

		return $this->selectFromTo(0, $rows - 1);
	}

    /**
     * (non-PHPdoc)
     *
     * @see \vxPHP\Orm\Query::selectFromTo()
     * @param $from
     * @param $to
     * @return Article[]
     * @throws Exception\ArticleException
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws \vxWeb\Model\ArticleCategory\Exception\ArticleCategoryException
     */
	public function selectFromTo($from, $to) {

		if(empty($this->columnSorts)) {
			throw new \RangeException("'" . __METHOD__ . "' requires a SORT criteria.");
		}

		if($to < $from) {
			throw new \RangeException("'to' value is less than 'from' value.");
		}

		$this->buildQueryString();
		$this->buildValuesArray();
		$this->sql .= ' LIMIT ' . (int) $from . ', ' . ($to - $from + 1);

		$ids = [];

		foreach($this->executeQuery() as $row) {
			$ids[] = $row['articlesid'];
		}

		return Article::getInstances($ids);

	}

	/**
	 * (non-PHPdoc)
	 * @see \vxPHP\Orm\Query::count()
	 */
	public function count() {
		// TODO: Auto-generated method stub

	}
}
