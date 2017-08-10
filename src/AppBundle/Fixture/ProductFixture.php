<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Fixture;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductFixture extends AbstractResourceFixture
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->scalarNode('name')->cannotBeEmpty()->end()
                ->scalarNode('short_description')->cannotBeEmpty()->end()
                ->scalarNode('min_player_count')->cannotBeEmpty()->end()
                ->scalarNode('max_player_count')->cannotBeEmpty()->end()
                ->scalarNode('min_age')->cannotBeEmpty()->end()
                ->arrayNode('images')->prototype('scalar')->end()->end()
        ;
    }
}
