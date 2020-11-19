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

use Behat\Mink\Element\NodeElement;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Webmozart\Assert\Assert;

class CreatePage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'app_frontend_game_play_create';
    }

    public function setPlayedAt(?string $playingDate): void
    {
        $playedAt = new \DateTime($playingDate);
        $this->getElement('played_at')->setValue($playedAt->format('Y-m-d'));
    }

    public function setDuration(?int $duration): void
    {
        $this->getElement('duration')->setValue($duration);
    }

    public function setPlayerCount(?int $playerCount): void
    {
        $this->getElement('player_count')->setValue($playerCount);
    }

    public function attachImage(string $path): void
    {
        $this->clickTabIfItsNotActive('images');

        $filesPath = $this->getParameter('files_path');

        $this->getDocument()->find('css', '.tab-content')->click();
        $this->getDocument()->clickLink('Add');

        $imageForm = $this->getLastImageElement();

        $imageForm->find('css', 'input[type="file"]')->attachFile($filesPath.$path);
    }

    public function submit(): void
    {
        $this->getDocument()->pressButton('Create');
    }

    public function clickTabIfItsNotActive(string $tabName): void
    {
        $attributesTab = $this->getElement('tab', ['%name%' => $tabName]);
        if (!$attributesTab->hasClass('active')) {
            $attributesTab->click();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'duration' => '#app_game_play_duration',
            'images' => '#app_game_play_images',
            'played_at' => '#app_game_play_playedAt',
            'player_count' => '#app_game_play_playerCount',
            'tab' => '.nav-tabs [href="#%name%"]',
        ]);
    }

    private function getLastImageElement(): NodeElement
    {
        $images = $this->getElement('images');
        $items = $images->findAll('css', 'div[data-form-collection="item"]');

        Assert::notEmpty($items);

        return end($items);
    }
}
