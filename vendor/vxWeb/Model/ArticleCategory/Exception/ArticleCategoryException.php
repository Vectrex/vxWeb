<?php
/*
 * This file is part of the vxPHP/vxWeb framework
 *
 * (c) Gregor Kofler <info@gregorkofler.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vxWeb\Model\ArticleCategory\Exception;

class ArticleCategoryException extends \Exception
{
    public const ARTICLECATEGORY_NOT_SAVED = 1;
    public const ARTICLECATEGORY_NOT_NESTED = 2;
    public const ARTICLECATEGORY_DOES_NOT_EXIST = 3;
    public const ARTICLECATEGORY_SORT_CALLBACK_NOT_CALLABLE = 4;
}