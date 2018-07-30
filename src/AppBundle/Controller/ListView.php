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

use ONGR\FilterManagerBundle\Filter\ViewData;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ListView
{
    /**
     * @var int
     */
    public $page;
    /**
     * @var int
     */
    public $limit;
    /**
     * @var int
     */
    public $total;
    /**
     * @var int
     */
    public $pages;
    /**
     * @var array
     */
    public $items = [];
    /**
     * @var ViewData[]
     */
    public $filters;
}
