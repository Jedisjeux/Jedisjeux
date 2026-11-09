<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Page\Backend\CustomerGroup;

use App\Tests\Behat\Behaviour\NamesIt;
use App\Tests\Behat\Behaviour\SpecifiesItsCode;
use App\Tests\Behat\Bridge\Crud\AbstractCreatePage;

class CreatePage extends AbstractCreatePage
{
    use NamesIt;
    use SpecifiesItsCode;

    public function getRouteName(): string
    {
        return 'sylius_backend_customer_group_create';
    }
}
