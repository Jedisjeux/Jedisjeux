<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Fixture;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class ArticleFixture extends AbstractResourceFixture
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->scalarNode('title')->cannotBeEmpty()->end()
                ->scalarNode('short_description')->end()
                ->scalarNode('status')->cannotBeEmpty()->end()
                ->scalarNode('publish_start_date')->cannotBeEmpty()->end()
                ->scalarNode('main_taxon')->cannotBeEmpty()->end()
                ->scalarNode('main_image')->cannotBeEmpty()->end()
                ->scalarNode('author')->cannotBeEmpty()->end()
                ->scalarNode('product')->cannotBeEmpty()->end()
        ;
    }
}
