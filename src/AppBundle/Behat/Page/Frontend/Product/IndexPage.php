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
        return 'sylius_product_index';
    }

    /**
     * @param string $productName
     *
     * @return bool
     */
    public function isProductOnList($productName)
    {
        return null !== $this->getDocument()->find('css', sprintf('.jeu-list h4:contains("%s")', $productName));
    }
}
