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

class CreatePage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'app_frontend_game_play_create';
    }

    /**
     * @param string|null $playingDate
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function setPlayedAt(?string $playingDate)
    {
        $playedAt = new \DateTime($playingDate);
        $this->getElement('played_at')->setValue($playedAt->format('Y-m-d'));
    }

    /**
     * @param int|null $duration
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function setDuration(?int $duration)
    {
        $this->getElement('duration')->setValue($duration);
    }

    /**
     * @param int|null $playerCount
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function setPlayerCount(?int $playerCount)
    {
        $this->getElement('player_count')->setValue($playerCount);
    }

    public function submit()
    {
        $this->getDocument()->pressButton('Create');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'duration' => '#app_game_play_duration',
            'played_at' => '#app_game_play_playedAt',
            'player_count' => '#app_game_play_playerCount',
        ]);
    }
}