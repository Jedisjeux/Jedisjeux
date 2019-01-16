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

namespace App\Behat\Page\Frontend;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class HomePage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
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
     * @return array
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getMostPopularArticlesTitles(): array
    {
        return array_map(
            function (NodeElement $element) {
                return $element->getText();
            },
            array_merge(
                $this->getElement('most_popular_articles')->findAll('css', '.lead'),
                $this->getElement('most_popular_articles')->findAll('css', 'h6')
            )
        );
    }

    /**
     * @return array
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getLatestArrivalsNames(): array
    {
        return array_map(
            function (NodeElement $element) {
                return $element->getText();
            },
            $this->getElement('latest_arrivals')->findAll('css', '.lead')
        );
    }

    /**
     * @return int
     *
     * @throws ElementNotFoundException
     */
    public function getRatingCount(): int
    {
        return (int) $this->getElement('rating_count')->getText();
    }

    /**
     * @return int
     *
     * @throws ElementNotFoundException
     */
    public function getProductCount(): int
    {
        return (int) $this->getElement('product_count')->getText();
    }

    /**
     * @return int
     *
     * @throws ElementNotFoundException
     */
    public function getArticleCount(): int
    {
        return (int) $this->getElement('article_count')->getText();
    }

    /**
     * @return int
     *
     * @throws ElementNotFoundException
     */
    public function getUserCount(): int
    {
        return (int) $this->getElement('user_count')->getText();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'article_count' => '#article-count',
            'latest_arrivals' => '#latest-arrivals',
            'latest_articles' => '#latest-articles',
            'logout_button' => '.app-logout-button',
            'most_popular_articles' => '#most-popular-articles',
            'product_count' => '#product-count',
            'rating_count' => '#rating-count',
            'user_count' => '#user-count',
        ]);
    }
}
