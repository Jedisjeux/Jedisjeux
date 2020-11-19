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

use App\Fixture\Factory\ProductBoxExampleFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ProductBoxFixture extends AbstractResourceFixture
{
    public function __construct(ObjectManager $objectManager, ProductBoxExampleFactory $exampleFactory)
    {
        parent::__construct($objectManager, $exampleFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'product_box';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->scalarNode('height')->cannotBeEmpty()->end()
                ->scalarNode('image')->cannotBeEmpty()->end()
                ->scalarNode('product')->cannotBeEmpty()->end()
                ->scalarNode('product_variant')->cannotBeEmpty()->end()
                ->scalarNode('author')->cannotBeEmpty()->end()
        ;
    }
}
