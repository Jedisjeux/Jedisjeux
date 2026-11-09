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

namespace App\Tests\Behat\Page\Frontend\ProductList;

use App\Tests\Behat\Page\Frontend\NamesListPageInterface;
use Behat\Mink\Element\NodeElement;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class ProductListItemIndexPage extends SymfonyPage implements NamesListPageInterface
{
    public function getRouteName(): string
    {
        return 'app_frontend_product_list_item_index';
    }

    /**
     * @return string[]
     */
    public function getNames(): array
    {
        return array_map(
            static fn (NodeElement $element): string => trim($element->getText()),
            $this->getDocument()->findAll('css', '#product-list .editorial-catalog-card h2'),
        );
    }
}
