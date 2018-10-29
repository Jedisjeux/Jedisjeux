<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Updater;

use Sylius\Component\Product\Model\ProductAssociationInterface;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class AbstractProductAssociationUpdater
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * AbstractProductAssociationUpdater constructor.
     *
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param ProductInterface                $product
     * @param ProductAssociationTypeInterface $associationType
     *
     * @return ProductAssociationInterface
     */
    protected function getProductAssociationByType(ProductInterface $product, ProductAssociationTypeInterface $associationType)
    {
        foreach ($product->getAssociations() as $association) {
            if ($association->getType()->getCode() === $associationType->getCode()) {
                return $association;
            }
        }

        /** @var ProductAssociationInterface $productAssociation */
        $productAssociation = $this->factory->createNew();
        $productAssociation->setType($associationType);
        $product->addAssociation($productAssociation);

        return $productAssociation;
    }
}
