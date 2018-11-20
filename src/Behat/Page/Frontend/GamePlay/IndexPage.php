<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Frontend\GamePlay;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class IndexPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
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

    /**
     * @return int
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function countGamePlays(): int
    {
        $gamePlays = $this->getElement('game_plays')->findAll('css', '.image-box');

        return count($gamePlays);
    }

    /**
     * @return bool
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function hasNoGamePlaysMessage(): bool
    {
        $gamePlaysContainerText = $this->getElement('game_plays')->getText();

        return false !== strpos($gamePlaysContainerText, 'There are no game plays');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'game_plays' => '#game-play-list',
        ]);
    }
}
