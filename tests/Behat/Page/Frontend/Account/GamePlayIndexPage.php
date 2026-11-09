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

use App\Tests\Behat\Page\Frontend\NamesListPageInterface;
use Behat\Mink\Element\NodeElement;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class GamePlayIndexPage extends SymfonyPage implements NamesListPageInterface
{
    public function getRouteName(): string
    {
        return 'app_frontend_account_game_play_index';
    }

    /**
     * @return string[]
     */
    public function getNames(): array
    {
        return array_map(
            static fn (NodeElement $element): string => trim($element->getText()),
            $this->getDocument()->findAll('css', '#game-play-list .editorial-game-play-card__game'),
        );
    }
}
