<?php

/*
 * This file is part of the Jedisjeux project.
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
    public function update(ProductAssociationInterface $association, ProductInterface $product)
    {
        $this->addProductAssociationOwnerToProduct($association, $product);
    }

    protected function addProductAssociationOwnerToProduct(ProductAssociationInterface $productAssociation, ProductInterface $product)
    {
        $associationType = $productAssociation->getType();

        $association = $this->getProductAssociationByType($product, $associationType);
        $association->addAssociatedProduct($productAssociation->getOwner());
    }
}
