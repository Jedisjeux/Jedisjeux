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
use Sylius\Component\Product\Model\ProductInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class AddProductAssociationOwnerToProductUpdater extends AbstractProductAssociationUpdater
{
    /**
     * @param ProductAssociationInterface $association
     * @param ProductInterface $product
     */
    public function update(ProductAssociationInterface $association, ProductInterface $product)
    {
        $this->addProductAssociationOwnerToProduct($association, $product);
    }

    /**
     * @param ProductAssociationInterface $productAssociation
     * @param ProductInterface $product
     */
    protected function addProductAssociationOwnerToProduct(ProductAssociationInterface $productAssociation, ProductInterface $product)
    {
        $associationType = $productAssociation->getType();

        $association = $this->getProductAssociationByType($product, $associationType);
        $association->addAssociatedProduct($productAssociation->getOwner());
    }
}
