<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\ProductVariant;

use App\Tests\Behat\Behaviour\NamesIt;
use Monofony\Bundle\AdminBundle\Tests\Behat\Crud\AbstractCreatePage;

class CreatePage extends AbstractCreatePage
{
    use NamesIt;

    public function getRouteName(): string
    {
        return 'sylius_backend_product_variant_create';
    }
}
