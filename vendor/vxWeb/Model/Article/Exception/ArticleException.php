<?php
    /*
     * This file is part of the vxPHP/vxWeb framework
     *
     * (c) Gregor Kofler <info@gregorkofler.com>
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     */

    namespace vxWeb\Model\Article\Exception;

    class ArticleException extends \Exception
    {
        public const int ARTICLE_DOES_NOT_EXIST = 1;
        public const int ARTICLE_HEADLINE_NOT_SET = 2;
        public const int ARTICLE_CATEGORY_NOT_SET = 3;
    }
