<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerPriceFixture extends AbstractResourceFixture
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'dealer_price';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->scalarNode('name')->cannotBeEmpty()->end()
                ->scalarNode('price')->cannotBeEmpty()->end()
                ->scalarNode('url')->cannotBeEmpty()->end()
                ->scalarNode('barcode')->end()
                ->scalarNode('status')->cannotBeEmpty()->end()
                ->scalarNode('dealer')->cannotBeEmpty()->end()
                ->scalarNode('product')->cannotBeEmpty()->end()

        ;
    }
}
