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

use App\Fixture\Factory\DealerPriceExampleFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class DealerPriceFixture extends AbstractResourceFixture
{
    public function __construct(ObjectManager $objectManager, DealerPriceExampleFactory $exampleFactory)
    {
        parent::__construct($objectManager, $exampleFactory);
    }

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
