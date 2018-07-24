<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class AppView
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;

    /**
     * @var \DateTimeInterface
     */
    public $createdAt;

    /**
     * @var ArticleView|null
     */
    public $article;

    /**
     * @var ImageView|null
     */
    public $image;

    /**
     * @var PersonView|null
     */
    public $person;

    /**
     * @var ProductView|null
     */
    public $product;

    /**
     * @var TopicView|null
     */
    public $topic;
}
