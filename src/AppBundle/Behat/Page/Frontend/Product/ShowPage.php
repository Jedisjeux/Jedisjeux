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

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'name' => 'h1.page-title',
            'mechanisms' => '#product-mechanisms',
            'themes' => '#product-themes',
            'designers' => '#product-designers',
            'artists' => '#product-artists',
            'publishers' => '#product-publishers',
        ]);
    }
}
