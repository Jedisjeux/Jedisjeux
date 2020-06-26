<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\Product;

use App\Tests\Behat\Page\Backend\Crud\CreatePage as BaseCreatePage;
use App\Entity\GameAward;
use Behat\Mink\Exception\ElementNotFoundException;

class CreatePageFromBgg extends CreatePage
{
    public function getRouteName(): string
    {
        return 'sylius_admin_product_from_bgg_new';
    }
}
