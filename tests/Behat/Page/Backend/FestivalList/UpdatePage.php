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

namespace App\Tests\Behat\Page\Backend\FestivalList;

use App\Tests\Behat\Behaviour\NamesIt;
use App\Tests\Behat\Bridge\Crud\AbstractUpdatePage;

class UpdatePage extends AbstractUpdatePage
{
    use NamesIt;

    public function getRouteName(): string
    {
        return 'app_backend_festival_list_update';
    }
}
