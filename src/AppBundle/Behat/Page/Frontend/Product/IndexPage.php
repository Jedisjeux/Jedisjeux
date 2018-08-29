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
class IndexPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'sylius_frontend_product_index';
    }

    /**
     * @return int
     */
    public function countProductsItems()
    {
        $productsList = $this->getDocument()->find('css', '#product-list');

        $products = $productsList->findAll('css', '.listing-item');

        return count($products);
    }

    /**
     * @return string
     */
    public function getFirstProductNameFromList()
    {
        $productsList = $this->getDocument()->find('css', '#product-list');

        return $productsList->find('css', '.listing-item:first-child h3')->getText();
    }

    /**
     * @param string $productName
     *
     * @return bool
     */
    public function isProductOnList($productName)
    {
        return null !== $this->getDocument()->find('css', sprintf('#product-list h3:contains("%s")', $productName));
    }
}
