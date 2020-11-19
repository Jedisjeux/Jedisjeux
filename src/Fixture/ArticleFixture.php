<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture;

use App\Fixture\Factory\ArticleExampleFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class ArticleFixture extends AbstractResourceFixture
{
    public function __construct(ObjectManager $objectManager, ArticleExampleFactory $exampleFactory)
    {
        parent::__construct($objectManager, $exampleFactory);
    }

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
                ->scalarNode('material_rating')->cannotBeEmpty()->end()
                ->scalarNode('rules_rating')->cannotBeEmpty()->end()
                ->scalarNode('lifetime_rating')->cannotBeEmpty()->end()
        ;
    }
}
