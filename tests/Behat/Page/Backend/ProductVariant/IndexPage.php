<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\ProductVariant;

use Monofony\Bridge\Behat\Crud\AbstractIndexPage;

class IndexPage extends AbstractIndexPage
{
    public function getRouteName(): string
    {
        return 'sylius_backend_product_variant_index';
    }
}
