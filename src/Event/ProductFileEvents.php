<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Event;

class ProductFileEvents
{
    const POST_CREATE = 'app.product_file.post_create';

    private function __construct()
    {
    }
}
