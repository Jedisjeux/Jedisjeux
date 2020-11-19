<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\GamePlay;

use Monofony\Bridge\Behat\Crud\AbstractUpdatePage;

class UpdatePage extends AbstractUpdatePage
{
    public function getRouteName(): string
    {
        return 'app_backend_game_play_update';
    }

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
