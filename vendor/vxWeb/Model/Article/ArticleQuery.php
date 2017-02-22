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
use vxWeb\Model\Article\ArticleCategory;

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
 * @version 0.4.0 2016-05-14
 */
class ArticleQuery extends Query {

	/**
	 * provide initial database connection
	 * currently only allows a Mysqli backend
	 *
	 * @param DatabaseInterface $dbConnection
	 */
	public function __construct(DatabaseInterface $dbConnection) {

		$this->table		= 'articles';
		$this->alias		= 'a';
		$this->columns		= array('a.articlesID');

		parent::__construct($dbConnection);

	}

	/**
	 * add WHERE clause that filters for $category
	 *
	 * @param ArticleCategory $category
	 * 
	 * @return \vxPHP\Orm\Custom\ArticleQuery
	 */
	public function filterByCategory(ArticleCategory $category) {

		$this->addCondition("a.articlecategoriesID = ?", $category->getId());
		return $this;

	}
	
	/**
	 * add WHERE clause that removes all unpublished articles from resultset
	 * 
	 * @return \vxPHP\Orm\Custom\ArticleQuery
	 */
	public function filterPublished() {

		$this->addCondition("a.published = 1");
		return $this;

	}

	/**
	 * add WHERE clause that removes all published articles from resultset
	 * 
	 * @return \vxPHP\Orm\Custom\ArticleQuery
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
	 * @return \vxPHP\Orm\Custom\ArticleQuery
	 */
	public function filterByCategoryNames(array $categoryNames) {
		$this->innerJoin('articlecategories c', 'c.articlecategoriesID = a.articlecategoriesID');
		$this->addCondition('c.Alias', $categoryNames, 'IN');
		return $this;
	}
	
	/**
	 * add WHERE clause that returns articles where $date is between Article::displayFrom and Article::displayUntil
	 * $date defaults to current date
	 * 
	 * @param \DateTime $date
	 * 
	 * @return \vxPHP\Orm\Custom\ArticleQuery
	 */
	public function filterByDisplayFromToUntil(\DateTime $date = NULL) {

		if(!$date) {
			$date = new \DateTime();
		}
		
		$this->addCondition("a.Display_from IS NULL OR a.Display_from <= ?", $date->format('Y-m-d'));
		$this->addCondition("a.Display_until IS NULL OR a.Display_until >= ?", $date->format('Y-m-d'));

		return $this;
	}

	/**
	 * executes query and returns array of Article instances
	 *
	 * @see \vxPHP\Orm\Query::select()
	 * @return Article[]
	 */
	public function select() {

		$this->buildQueryString();
		$this->buildValuesArray();

		$ids = array();

		foreach($this->executeQuery() as $row) {
			$ids[] = $row['articlesID'];
		}

		return Article::getInstances($ids);
	}

	/**
	 * adds LIMIT clause, executes query and returns array of Article instances
	 *
	 * @see \vxPHP\Orm\Query::selectFirst()
	 * @param number $rows
	 * @return Article[]
	 * @throws \RangeException
	 */
	public function selectFirst($rows = 1) {

		if(empty($this->columnSorts)) {
			throw new \RangeException("'" . __METHOD__ . "' requires a SORT criteria.");
		}

		return $this->selectFromTo(0, $rows - 1);
	}

	/**
	/* (non-PHPdoc)
	 * @see \vxPHP\Orm\Query::selectFromTo()
	 * @return Article[]
	 * @throws \RangeException
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

		$ids = array();

		foreach($this->executeQuery() as $row) {
			$ids[] = $row['articlesID'];
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
