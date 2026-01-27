<?php
    /*
     * This file is part of the vxPHP/vxWeb framework
     *
     * (c) Gregor Kofler <info@gregorkofler.com>
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     */

    namespace vxWeb\Model\MetaFile;

    use vxPHP\Database\DatabaseInterface;
    use vxPHP\Orm\Query;
    use vxWeb\Model\Article\Article;

    /**
     * query object which returns an array of MetaFile objects
     *
     * @example
     *
     * $articles =    vxPHP\File\MetaFileQuery::create($db)->
     *                filterByFolder($myFolder)->
     *                filterByReference('articles', $myArticle->getId())->
     *                sortBy('customSort', FALSE)->
     *                select();
     *
     * @author Gregor Kofler
     *
     * @version 0.3.0 2021-05-22
     */
    class MetaFileQuery extends Query
    {
        /**
         * initialize a query
         *
         * @param DatabaseInterface $dbConnection
         */
        public function __construct(DatabaseInterface $dbConnection)
        {
            $this->table = 'files f';
            parent::__construct($dbConnection);
        }

        /**
         * add an appropriate WHERE clause that filters for $metaFolder
         *
         * @param MetaFolder $folder
         * @return MetaFileQuery
         */
        public function filterByFolder(MetaFolder $folder): self
        {
            $this->addCondition("f.foldersid = ?", $folder->getId());
            return $this;
        }

        /**
         * add an appropriate WHERE clause that filters metafiles linked to the given article
         *
         * @param Article $article
         * @return MetaFileQuery
         */

        public function filterByArticle(Article $article): self
        {
            if ($article->getId()) {
                $this->innerJoin('articles_files af', 'af.filesid = f.filesid');
                $this->addCondition("af.articlesid = ?", $article->getId());
            }

            return $this;
        }

        /**
         * executes the query and returns an array of MetaFile instances
         *
         * @return array
         * @throws \Throwable
         */
        public function select(): array
        {
            $this->buildQueryString();
            $this->buildValuesArray();
            $rows = $this->executeQuery();

            $ids = [];

            foreach ($rows as $row) {
                $ids[] = $row['filesid'];
            }

            return MetaFile::getInstancesByIds($ids);
        }

        /**
         * adds LIMIT clause, executes the query, and returns an array of MetaFile instances
         *
         * @param int $count
         * @return array
         * @throws \Throwable
         */
        public function selectFirst(int $count = 1): array
        {
            $this->buildQueryString();
            $this->buildValuesArray();

            $this->sql .= ' LIMIT ' . $count;

            $rows = $this->executeQuery();

            $ids = [];

            foreach ($rows as $row) {
                $ids[] = $row['filesid'];
            }

            return MetaFile::getInstancesByIds($ids);
        }

        /**
         * /* (non-PHPdoc)
         * @see Query::selectFromTo()
         */
        public function selectFromTo(int $from, int $to): array
        {
            // TODO: Auto-generated method stub
            return [];
        }

        /**
         * (non-PHPdoc)
         * @see Query::count()
         */
        public function count(): int
        {
            // TODO: Auto-generated method stub
            return 0;
        }
    }
