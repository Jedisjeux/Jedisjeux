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

use App\Fixture\Factory\ProductExampleFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ProductFixture extends AbstractResourceFixture
{
    public function __construct(ObjectManager $objectManager, ProductExampleFactory $exampleFactory)
    {
        parent::__construct($objectManager, $exampleFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
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
                ->scalarNode('code')->cannotBeEmpty()->end()
                ->scalarNode('name')->cannotBeEmpty()->end()
                ->scalarNode('short_description')->cannotBeEmpty()->end()
                ->scalarNode('min_player_count')->cannotBeEmpty()->end()
                ->scalarNode('max_player_count')->cannotBeEmpty()->end()
                ->scalarNode('min_duration')->cannotBeEmpty()->end()
                ->scalarNode('max_duration')->cannotBeEmpty()->end()
                ->scalarNode('min_age')->cannotBeEmpty()->end()
                ->scalarNode('released_at')->cannotBeEmpty()->end()
                ->scalarNode('released_at_precision')->cannotBeEmpty()->end()
                ->scalarNode('created_at')->cannotBeEmpty()->end()
                ->scalarNode('main_taxon')->cannotBeEmpty()->end()
                ->scalarNode('box_content')->end()
                ->arrayNode('images')->prototype('scalar')->end()->end()
                ->arrayNode('designers')->prototype('scalar')->end()->end()
                ->arrayNode('artists')->prototype('scalar')->end()->end()
                ->arrayNode('publishers')->prototype('scalar')->end()->end()
                ->arrayNode('mechanisms')->prototype('scalar')->end()->end()
                ->arrayNode('themes')->prototype('scalar')->end()->end()
                ->scalarNode('status')->cannotBeEmpty()->end()
        ;
    }
}
