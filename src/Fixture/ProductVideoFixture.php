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

use App\Fixture\Factory\ProductVideoExampleFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ProductVideoFixture extends AbstractResourceFixture
{
    public function __construct(ObjectManager $objectManager, ProductVideoExampleFixture $exampleFactory)
    {
        parent::__construct($objectManager, $exampleFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'product_video';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->scalarNode('title')->cannotBeEmpty()->end()
                ->scalarNode('path')->cannotBeEmpty()->end()
                ->scalarNode('product')->cannotBeEmpty()->end()
        ;
    }
}
