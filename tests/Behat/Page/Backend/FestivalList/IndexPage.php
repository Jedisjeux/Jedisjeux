<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\FestivalList;

use Monofony\Bridge\Behat\Crud\AbstractIndexPage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class IndexPage extends AbstractIndexPage
{
    public function getRouteName(): string
    {
        return 'app_backend_festival_list_index';
    }
}
