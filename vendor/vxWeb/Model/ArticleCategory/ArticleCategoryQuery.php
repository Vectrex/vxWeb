<?php
/*
 * This file is part of the vxPHP/vxWeb framework
 *
 * (c) Gregor Kofler <info@gregorkofler.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vxWeb\Model\ArticleCategory;

use vxPHP\Orm\Query;
use vxPHP\Orm\QueryInterface;
use vxPHP\Database\DatabaseInterface;

/**
 * query object which returns an array of ArticleCategory objects
 *
 * @author Gregor Kofler
 * @version 0.1.0 2017-04-26
 */
class ArticleCategoryQuery extends Query {

	/**
	 * provide initial database connection
	 *
	 * @param DatabaseInterface $dbConnection
	 */
	public function __construct(DatabaseInterface $dbConnection) {
		
		$this->table		= 'articlecategories';
		$this->alias		= 'ac';
		$this->columns		= ['ac.articlecategoriesid'];
		
		parent::__construct($dbConnection);

	}
	
	
	/**
	 * executes query and returns array of ArticleCategory instances
	 *
	 * @see \vxPHP\Orm\Query::select()
	 * @return ArticleCategory[]
	 */
	public function select() {
		
		$this->buildQueryString();
		$this->buildValuesArray();
		
		$ids = [];
		
		foreach($this->executeQuery() as $row) {
			$ids[] = $row['articlecategoriesid'];
		}

		return ArticleCategory::getInstances($ids);
	}
	
	/**
	 * adds LIMIT clause, executes query and returns array of ArticleCategory instances
	 *
	 * @see \vxPHP\Orm\Query::selectFirst()
	 * @param number $rows
	 * @return ArticleCategory[]
	 * @throws \RangeException
	 */
	public function selectFirst($rows = 1) {
		
		if(empty($this->columnSorts)) {
			throw new \RangeException("'" . __METHOD__ . "' requires a SORT criteria.");
		}
		
		return $this->selectFromTo(0, $rows - 1);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \vxPHP\Orm\Query::selectFromTo()
	 * @return ArticleCategory[]
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
		
		$ids = [];
		
		foreach($this->executeQuery() as $row) {
			$ids[] = $row['articlecategoriesid'];
		}
		
		return Article::getInstances($ids);
		
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \vxPHP\Orm\Query::count()
	 */
	public function count() {
		// TODO Auto-generated method stub
	}

}