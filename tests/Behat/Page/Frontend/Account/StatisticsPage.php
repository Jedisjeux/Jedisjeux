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

namespace App\Tests\Behat\Page\Frontend\Account;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class StatisticsPage extends SymfonyPage
{
    public function getRouteName(): string
    {
        return 'app_frontend_account_statistics';
    }

    public function getGamePlayCount(): int
    {
        return (int) $this->getElement('game_play_count')->getText();
    }

    public function getProductCount(): int
    {
        return (int) $this->getElement('product_count')->getText();
    }

    /**
     * @return array<string, int> Game play count indexed by game name, the most played first
     */
    public function getMostPlayedGames(): array
    {
        $games = [];

        foreach ($this->getElement('most_played_games')->findAll('css', '[data-game]') as $game) {
            $games[(string) $game->getAttribute('data-game')] = (int) $game->getAttribute('data-game-play-count');
        }

        return $games;
    }

    public function hasNoGamePlay(): bool
    {
        return $this->hasElement('empty');
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'empty' => '#statistics-empty',
            'game_play_count' => '#statistics-game-play-count',
            'most_played_games' => '#statistics-most-played',
            'product_count' => '#statistics-product-count',
        ]);
    }
}
