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

namespace App\Tests\Behat\Page\Frontend\Product;

use Behat\Mink\Element\NodeElement;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class ReleasesPage extends SymfonyPage
{
    public function getRouteName(): string
    {
        return 'sylius_frontend_product_release_index';
    }

    /**
     * @param string $period this-month, next-months or previous-months
     *
     * @return string[]
     */
    public function getProductNames(string $period): array
    {
        $section = $this->getDocument()->find('css', sprintf('#releases-%s', $period));

        if (null === $section) {
            return [];
        }

        return array_map(
            static fn (NodeElement $name): string => trim($name->getText()),
            $section->findAll('css', '.editorial-catalog-card h2'),
        );
    }
}
