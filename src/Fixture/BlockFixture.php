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

use App\Fixture\Factory\BlockExampleFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class BlockFixture extends AbstractResourceFixture
{
    public function __construct(ObjectManager $objectManager, BlockExampleFactory $exampleFactory)
    {
        parent::__construct($objectManager, $exampleFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'block';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->scalarNode('article')->cannotBeEmpty()->end()
                ->scalarNode('title')->cannotBeEmpty()->end()
                ->scalarNode('body')->cannotBeEmpty()->end()
                ->scalarNode('image')->cannotBeEmpty()->end()
                ->scalarNode('image_position')->cannotBeEmpty()->end()
        ;
    }
}
