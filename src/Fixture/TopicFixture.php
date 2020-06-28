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

use App\Fixture\Factory\TopicExampleFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class TopicFixture extends AbstractResourceFixture
{
    public function __construct(ObjectManager $objectManager, TopicExampleFactory $exampleFactory)
    {
        parent::__construct($objectManager, $exampleFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'topic';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
            ->scalarNode('title')->cannotBeEmpty()->end()
            ->scalarNode('main_taxon')->cannotBeEmpty()->end()
            ->scalarNode('article')->cannotBeEmpty()->end()
            ->scalarNode('game_play')->cannotBeEmpty()->end()
        ;
    }
}
