<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace App\Event;


class ProductBoxEvents
{
    const POST_CREATE = 'app.product_box.post_create';

    private function __construct()
    {
    }
}
