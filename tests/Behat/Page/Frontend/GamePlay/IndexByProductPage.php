<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Frontend\GamePlay;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class IndexByProductPage extends IndexPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'app_frontend_game_play_index_by_product';
    }
}
