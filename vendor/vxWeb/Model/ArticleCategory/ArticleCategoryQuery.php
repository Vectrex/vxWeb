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

    use vxPHP\Database\DatabaseInterface;
    use vxPHP\Orm\Query;
    use vxWeb\Model\Article\Article;

    /**
     * query object which returns an array of ArticleCategory objects
     *
     * @author Gregor Kofler
     * @version 0.3.0 2026-01-27
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
         * executes a query and returns an array of ArticleCategory instances
         *
         * @return ArticleCategory[]
         * @throws \Throwable
         * @see Query::select()
         */
        public function select(): array
        {
            $this->buildQueryString();
            $this->buildValuesArray();

            $ids = [];

            foreach ($this->executeQuery() as $row) {
                $ids[] = $row['articlecategoriesid'];
            }

            return ArticleCategory::getInstances($ids);
        }

        /**
         * adds LIMIT clause, executes a query, and returns an array of ArticleCategory instances
         *
         * @param number $count
         * @return ArticleCategory[]
         * @throws \RangeException|\Throwable
         * @see Query::selectFirst()
         */
        public function selectFirst($count = 1): array
        {
            if (empty($this->columnSorts)) {
                throw new \RangeException("'" . __METHOD__ . "' requires a SORT criteria.");
            }

            return $this->selectFromTo(0, $count - 1);
        }

        /**
         * (non-PHPdoc)
         * @return ArticleCategory[]
         * @throws \RangeException|\Throwable
         * @see Query::selectFromTo()
         */
        public function selectFromTo($from, $to): array
        {
            if (empty($this->columnSorts)) {
                throw new \RangeException("'" . __METHOD__ . "' requires a SORT criteria.");
            }

            if ($to < $from) {
                throw new \RangeException("'to' value is less than 'from' value.");
            }

            $this->buildQueryString();
            $this->buildValuesArray();
            $this->sql .= ' LIMIT ' . (int)$from . ', ' . ($to - $from + 1);

            $ids = [];

            foreach ($this->executeQuery() as $row) {
                $ids[] = $row['articlecategoriesid'];
            }

            return Article::getInstances($ids);
        }

        /**
         *
         * {@inheritdoc}
         *
         * @see Query::count()
         */
        public function count(): int
        {
            return 0;
            // TODO Auto-generated method stub
        }
    }