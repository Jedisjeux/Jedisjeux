<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\GamePlay;

use App\Tests\Behat\Page\Backend\Crud\UpdatePage as BaseUpdatePage;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
class UpdatePage extends BaseUpdatePage
{
    /**
     * @param string $playedAt
     */
    public function changePlayedAt($playedAt)
    {
        $this->getElement('played_at')->setValue($playedAt);
    }

    /**
     * @param string $duration
     */
    public function changeDuration($duration)
    {
        $this->getElement('duration')->setValue($duration);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'played_at' => '#app_game_play_playedAt',
            'duration' => '#app_game_play_duration',
        ]);
    }
}
