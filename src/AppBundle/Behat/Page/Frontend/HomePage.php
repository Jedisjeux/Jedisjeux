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

namespace AppBundle\Behat\Page\Frontend;

use Behat\Mink\Element\NodeElement;
use AppBundle\Behat\Page\SymfonyPage;

class HomePage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'app_homepage';
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        return $this->getDocument()->getContent();
    }

    /**
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function logOut(): void
    {
        $this->getElement('logout_button')->click();
    }

    /**
     * @return bool
     */
    public function hasLogoutButton(): bool
    {
        return $this->hasElement('logout_button');
    }

    /**
     * @return array
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getLatestArticlesTitles(): array
    {
        return array_map(
            function (NodeElement $element) {
                return $element->getText();
            },
            array_merge(
                $this->getElement('latest_articles')->findAll('css', '.lead'),
                $this->getElement('latest_articles')->findAll('css', 'h6')
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'latest_articles' => '#latest-articles',
            'logout_button' => '.app-logout-button',
        ]);
    }
}
