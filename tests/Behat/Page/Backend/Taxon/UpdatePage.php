<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\Taxon;

use App\Tests\Behat\Behaviour\NamesIt;
use Monofony\Bridge\Behat\Crud\AbstractUpdatePage;

class UpdatePage extends AbstractUpdatePage
{
    use NamesIt;

    public function getRouteName(): string
    {
        return 'sylius_backend_taxon_update';
    }
}
