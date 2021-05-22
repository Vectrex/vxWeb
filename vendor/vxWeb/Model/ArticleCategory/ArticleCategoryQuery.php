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

use vxPHP\Application\Exception\ApplicationException;
use vxPHP\Orm\Query;
use vxPHP\Database\DatabaseInterface;
use vxWeb\Model\Article\Article;

/**
 * query object which returns an array of ArticleCategory objects
 *
 * @author Gregor Kofler
 * @version 0.2.0 2021-05-22
 */
class ArticleCategoryQuery extends Query
{
	/**
	 * provide initial database connection
	 *
	 * @param DatabaseInterface $dbConnection
	 */
	public function __construct(DatabaseInterface $dbConnection)
    {
		$this->table = 'articlecategories';
		$this->alias = 'ac';
		$this->columns = ['ac.articlecategoriesid'];
		
		parent::__construct($dbConnection);
	}


    /**
     * executes query and returns array of ArticleCategory instances
     *
     * @return ArticleCategory[]
     * @throws Exception\ArticleCategoryException
     * @throws ApplicationException
     * @see \vxPHP\Orm\Query::select()
     */
	public function select(): array
    {
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
	public function selectFirst($rows = 1): array
    {
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
	public function selectFromTo($from, $to): array
    {
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
	public function count(): int
    {
		// TODO Auto-generated method stub
	}
}