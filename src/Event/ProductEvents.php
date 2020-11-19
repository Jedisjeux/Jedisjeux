<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Event;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductEvents
{
    const PRE_CREATE = 'sylius.product.pre_create';
    const PRE_UPDATE = 'sylius.product.pre_update';

    private function __construct()
    {
    }
}
