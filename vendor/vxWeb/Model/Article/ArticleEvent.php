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

    use vxPHP\Observer\Event;
    use vxPHP\Observer\PublisherInterface;

    class ArticleEvent extends Event
    {
        const string BEFORE_ARTICLE_SAVE = 'ArticleEvent.beforeArticleSave';
        const string AFTER_ARTICLE_SAVE = 'ArticleEvent.afterArticleSave';
        const string BEFORE_ARTICLE_DELETE = 'ArticleEvent.beforeArticleDelete';
        const string AFTER_ARTICLE_DELETE = 'ArticleEvent.afterArticleDelete';

        public function __construct (string $eventName, PublisherInterface $publisher)
        {
            // optional event type-specific stuff happens here

            parent::__construct($eventName, $publisher);
        }
    }