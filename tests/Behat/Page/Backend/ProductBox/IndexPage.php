<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\ProductBox;

use Monofony\Bridge\Behat\Crud\AbstractIndexPage;

class IndexPage extends AbstractIndexPage
{
    public function getRouteName(): string
    {
        return 'app_backend_product_box_index';
    }
}
