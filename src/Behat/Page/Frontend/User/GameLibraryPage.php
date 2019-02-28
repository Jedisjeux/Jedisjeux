<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Frontend\User;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class GameLibraryPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'app_frontend_user_games_library';
    }

    /**
     * @return int
     */
    public function countGames(): int
    {
        $gameLibrary = $this->getDocument()->find('css', '#article-list');

        $games = $gameLibrary->findAll('css', 'a');

        return count($games);
    }
}
