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
     * @param string|null $title
     */
    public function setPlayingDate(?string $playingDate)
    {
        $playedAt = new \DateTime($playingDate);
        $this->getElement('played_at')->setValue($playedAt->format('d-m-Y'));
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
            'played_at' => '#app_game_play_playedAt',
        ]);
    }
}