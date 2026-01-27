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

    use vxPHP\Application\Application;
    use vxPHP\Database\Util;
    use vxWeb\Model\Article\Article;
    use vxWeb\Model\ArticleCategory\Exception\ArticleCategoryException;

    /**
     * Mapper class for articlecategories, stored in table `articlecategories`
     *
     * @author Gregor Kofler
     * @version 0.7.0 2026-01-27
     */
    class ArticleCategory
    {
        /**
         * instances index by primary key
         *
         * @var ArticleCategory[]
         */
        private static array $instancesById = [];

        /**
         * instances index by alias
         *
         * @var ArticleCategory[]
         */
        private static array $instancesByAlias = [];

        /**
         * primary key of category
         *
         * @var int
         */
        private int $id;

        /**
         * unique alias of category
         *
         * @var string
         */
        private string $alias;

        /**
         * nesting information of category
         *
         * @var int $level
         * @var int $l
         * @var int $r
         */
        private ?int $level, $l, $r;

        /**
         * the descriptive title of the category
         *
         * @var string
         */
        private string $title;

        /**
         * an optional value, which can be used for sorting
         *
         * @var int
         */
        private int $customSort;

        /**
         * articles belonging to the category
         *
         * @var null|Article[]
         */
        private ?array $articles;

        /**
         * the parent category
         *
         * @var null|ArticleCategory
         */
        private ?ArticleCategory $parentCategory;

        /**
         * create a new category
         * a freshly created category has no representation in the database
         *  yet, and persistence requires a save()
         *
         * @param string $title
         * @param ArticleCategory|null $parentCategory
         */
        public function __construct(string $title, ?self $parentCategory = null)
        {
            $this->parentCategory = $parentCategory;
            $this->title = $title;
        }

        public function __toString()
        {
            return $this->alias;
        }

        public function __destruct()
        {
            // @todo clean up nesting
        }

        /**
         * save category
         *
         * @todo only INSERTs are supported, UPDATE has to be implemented
         */
        public function save(): self
        {
            $db = Application::getInstance()->getVxPDO();

            if (is_null($this->parentCategory)) {

                // prepare to insert a top level category

                $rows = $db->doPreparedQuery('SELECT MAX(r) + 1 AS l FROM articlecategories');
                $this->l = $rows[0]['l'] ?? 0;
                $this->r = $rows[0]['l'] + 1;
                $this->level = 0;
            } else {

                // prepare to insert a subcategory

                // in case the parent category has not been saved - save it

                if (is_null($this->parentCategory->id)) {
                    $this->parentCategory->save();
                }

                $nsData = $this->parentCategory->getNsData();

                $this->l = $nsData['r'];
                $this->r = $nsData['r'] + 1;
                $this->level = $nsData['level'] + 1;

                $db->execute('UPDATE articlecategories SET r = r + 2 WHERE r >= ?', [$this->l]);
                $db->execute('UPDATE articlecategories SET l = l + 2 WHERE l > ?', [$this->r]);
            }

            // insert category data

            $this->alias = Util::getAlias($db, $this->title, 'articlecategories');

            $this->id = $db->insertRecord('articlecategories', [
                'alias' => $this->alias,
                'l' => $this->l,
                'r' => $this->r,
                'level' => $this->level,
                'title' => $this->title,
                'customsort' => $this->customSort
            ]);

            self::$instancesByAlias [$this->alias] = $this;
            self::$instancesById [$this->id] = $this;

            return $this;
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getAlias(): ?string
        {
            return $this->alias;
        }

        public function getTitle(): string
        {
            return $this->title;
        }

        public function setTitle(string $title): self
        {
            $this->title = trim($title);
            return $this;
        }

        public function getCustomSort(): ?int
        {
            return $this->customSort;
        }

        public function setCustomSort(int $ndx): self
        {
            $this->customSort = $ndx;
            return $this;
        }

        /**
         * retrieves all articles assigned to this article category
         *
         * @return array
         * @throws \Throwable
         */
        public function getArticles(): array
        {
            if (is_null($this->articles)) {
                $this->articles = Article::getArticlesForCategory($this);
            }
            return $this->articles;
        }

        public function setParent(self $parent): self
        {

            // @todo update nesting of previous parent category

            $this->parentCategory = $parent;
            return $this;
        }

        private function getNsData(): array
        {
            // re-read data for already stored categories, if not already retrieved

            if ($this->id && is_null($this->r) && is_null($this->l) && is_null($this->level)) {
                $rows = Application::getInstance()->getVxPDO()->doPreparedQuery('SELECT r, l, level FROM articlecategories c WHERE articlecategoriesid = ?', [$this->id]);
                $this->r = $rows['r'];
                $this->l = $rows['l'];
                $this->level = $rows['level'];
            }
            return ['r' => $this->r, 'l' => $this->l, 'level' => $this->level];
        }

        /**
         * returns an articlecategory instance identified by numeric id or alias
         *
         * @param mixed $id
         * @return ArticleCategory
         * @throws \Throwable
         */
        public static function getInstance(string|int $id): self
        {
            $db = Application::getInstance()->getVxPDO();

            if (is_numeric($id)) {
                $id = (int) $id;
                if (isset(self::$instancesById[$id])) {
                    return self::$instancesById[$id];
                }

                $col = 'articlecategoriesid';
            } else {
                if (isset(self::$instancesByAlias[$id])) {
                    return self::$instancesByAlias[$id];
                }

                $col = 'alias';
            }

            $row = $db->doPreparedQuery("
                SELECT
                    c.*,
                    p.articlecategoriesid AS parentid
                FROM
                    articlecategories c
                    LEFT JOIN articlecategories p ON p.l < c.l AND p.r > c.r AND p.level = c.level - 1
                WHERE
                    c.$col = ?", [$id]
            )->current();

            if (!$row) {
                throw new ArticleCategoryException(sprintf("Category with %s '%s' does not exist.", $col, $id), ArticleCategoryException::ARTICLECATEGORY_DOES_NOT_EXIST);
            }

            if (!empty($row['level'])) {
                if (empty($row['parentid'])) {
                    throw new ArticleCategoryException(sprintf("Category '%s' not properly nested.", $row['title']), ArticleCategoryException::ARTICLECATEGORY_NOT_NESTED);
                }
                $cat = new self($row['title'], self::getInstance($row['parentid']));
            } else {
                $cat = new self($row['title']);
            }

            $cat->id = $row['articlecategoriesid'];
            $cat->alias = $row['alias'];
            $cat->r = $row['r'];
            $cat->l = $row['l'];
            $cat->level = $row['level'];
            $cat->customSort = $row['customsort'];

            self::$instancesByAlias[$cat->alias] = $cat;
            self::$instancesById[$cat->id] = $cat;

            return $cat;
        }

        /**
         * returns an array of ArticleCategory objects identified by numeric id or alias
         *
         * @param array $ids contains mixed category ids or alias
         * @return ArticleCategory[]
         * @throws \Throwable
         */
        public static function getInstances(array $ids): array
        {
            $db = Application::getInstance()->getVxPDO();

            $toRetrieveById = [];
            $toRetrieveByAlias = [];

            foreach ($ids as $id) {

                if (is_numeric($id)) {
                    $id = (int)$id;

                    if (!isset(self::$instancesById[$id])) {
                        $toRetrieveById[] = $id;
                    }
                } else if (!isset(self::$instancesByAlias[$id])) {
                    $toRetrieveByAlias[] = $id;
                }

                $where = [];

                if (count($toRetrieveById)) {
                    $where[] = 'c.articlecategoriesid IN (' . implode(',', array_fill(0, count($toRetrieveById), '?')) . ')';
                }
                if (count($toRetrieveByAlias)) {
                    $where[] = 'c.alias IN (' . implode(',', array_fill(0, count($toRetrieveByAlias), '?')) . ')';
                }

                if (count($where)) {

                    $rows = $db->doPreparedQuery('
					SELECT
						c.*,
						p.articlecategoriesid AS parentid
					FROM
						articlecategories c
						LEFT JOIN articlecategories p ON p.l < c.l AND p.r > c.r AND p.level = c.level - 1
					WHERE
						' . implode(' OR ', $where),
                        array_merge($toRetrieveById, $toRetrieveByAlias)
                    );

                    foreach ($rows as $row) {

                        if (!empty($row['level'])) {
                            if (empty($row['parentid'])) {
                                throw new ArticleCategoryException(sprintf("Category '%s' not properly nested.", $row['title']), ArticleCategoryException::ARTICLECATEGORY_NOT_NESTED);
                            }
                            $cat = new self($row['title'], self::getInstance($row['parentid']));
                        } else {
                            $cat = new self($row['title']);
                        }

                        $cat->id = $row['articlecategoriesid'];
                        $cat->alias = $row['alias'];
                        $cat->r = $row['r'];
                        $cat->l = $row['l'];
                        $cat->level = $row['level'];
                        $cat->customSort = $row['customsort'];

                        self::$instancesByAlias[$cat->alias] = $cat;
                        self::$instancesById[$cat->id] = $cat;
                    }
                }
            }

            $categories = [];

            foreach ($ids as $id) {
                $categories[] = self::getInstance($id);
            }

            return $categories;
        }


        /**
         * retrieve all available categories sorted by $sortCallback
         *
         * @param mixed $sortCallback
         * @return array categories
         * @throws \Throwable
         */
        public static function getArticleCategories(mixed $sortCallback = null): array
        {
            $cat = [];

            foreach (
                Application::getInstance()->getVxPDO()->doPreparedQuery('SELECT articlecategoriesID FROM articlecategories')
                as $r) {
                $cat[] = self::getInstance($r['articlecategoriesid']);
            }

            if (is_null($sortCallback)) {
                return $cat;
            }

            if (is_callable($sortCallback)) {
                usort($cat, $sortCallback);
                return $cat;
            }

            if (is_callable("self::$sortCallback")) {
                usort($cat, self::class . "::$sortCallback");
                return $cat;
            }

            throw new ArticleCategoryException(sprintf("'%s' is not callable.", $sortCallback), ArticleCategoryException::ARTICLECATEGORY_SORT_CALLBACK_NOT_CALLABLE);
        }
    }
