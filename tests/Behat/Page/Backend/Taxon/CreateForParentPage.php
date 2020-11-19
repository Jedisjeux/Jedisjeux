<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\Taxon;

class CreateForParentPage extends CreatePage
{
    public function getRouteName(): string
    {
        return 'sylius_backend_taxon_create_for_parent';
    }
}
