<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Page\Frontend\GamePlay;

use AppBundle\Behat\Page\SymfonyPage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class IndexPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'app_frontend_game_play_index';
    }

    /**
     * @param string $title
     *
     * @return bool
     */
    public function isProductOnList($title)
    {
        return null !== $this->getDocument()->find('css', sprintf('#game-play-list .lead:contains("%s")', $title));
    }
}
