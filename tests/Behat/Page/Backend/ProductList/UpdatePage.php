<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\ProductList;

use App\Tests\Behat\Behaviour\NamesIt;
use Monofony\Bridge\Behat\Crud\AbstractUpdatePage;

class UpdatePage extends AbstractUpdatePage
{
    use NamesIt;

    public function getRouteName(): string
    {
        return 'app_backend_product_list_update';
    }
}
