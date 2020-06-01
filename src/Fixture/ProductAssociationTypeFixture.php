<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Fixture;

use App\Fixture\Factory\ProductAssociationTypeExampleFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class ProductAssociationTypeFixture extends AbstractResourceFixture
{
    public function __construct(ObjectManager $objectManager, ProductAssociationTypeExampleFactory $exampleFactory)
    {
        parent::__construct($objectManager, $exampleFactory);
    }

    public function getName(): string
    {
        return 'product_association_type';
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
        ;
    }
}
