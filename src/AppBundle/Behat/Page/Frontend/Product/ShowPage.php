<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Page\Frontend\Product;

use AppBundle\Behat\Page\SymfonyPage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ShowPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'sylius_frontend_product_show';
    }

    /**
     * @return string
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getName(): string
    {
        return $this->getElement('name')->getText();
    }

    /**
     * @return \Behat\Mink\Element\NodeElement[]
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getMechanisms(): array
    {
        $mechanismsParagraph = $this->getElement('mechanisms');

        return $mechanismsParagraph->findAll('css', 'a');
    }

    /**
     * @return array
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getThemes(): array
    {
        $mechanisms = $this->getElement('themes');

        return $mechanisms->findAll('css', 'a');
    }

    /**
     * @return array
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getDesigners(): array
    {
        $designers = $this->getElement('designers');

        return $designers->findAll('css', 'a');
    }

    /**
     * @return array
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getArtists(): array
    {
        $artists = $this->getElement('artists');

        return $artists->findAll('css', 'a');
    }

    /**
     * @return array
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getPublishers(): array
    {
        $publishers = $this->getElement('publishers');

        return $publishers->findAll('css', 'a');
    }

    public function countReviews(): int
    {
        return count($this->getElement('reviews')->findAll('css', '.comment'));
    }

    public function countArticles(): int
    {
        return count($this->getElement('articles')->findAll('css', '.image-box'));
    }

    /**
     * @param string $title
     *
     * @return bool
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function hasReviewTitled(string $title): bool
    {
        return null !== $this->getElement('reviews')->find('css', sprintf('.comment:contains("%s")', $title));
    }

    /**
     * @param string $title
     *
     * @return bool
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function hasArticleTitled(string $title): bool
    {
        return null !== $this->getElement('articles')->find('css', sprintf('.image-box .lead:contains("%s")', $title));
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'articles' => '#articles',
            'artists' => '#product-artists',
            'designers' => '#product-designers',
            'mechanisms' => '#product-mechanisms',
            'name' => 'h1.page-title',
            'publishers' => '#product-publishers',
            'reviews' => '#reviews .comments',
            'themes' => '#product-themes',
        ]);
    }
}
