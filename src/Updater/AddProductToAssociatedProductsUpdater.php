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
class AddProductToAssociatedProductsUpdater extends AbstractProductAssociationUpdater
{
    /**
     * @param ProductInterface $product
     * @param ProductAssociationInterface $association
     */
    public function update(ProductInterface $product, ProductAssociationInterface $association)
    {
        $this->addProductToAssociatedProducts($product, $association);
    }

    /**
     * @param ProductAssociationInterface $association
     * @param ProductInterface $product
     */
    protected function addProductToAssociatedProducts(ProductInterface $product, ProductAssociationInterface $association)
    {
        $associationType = $association->getType();

        foreach ($association->getAssociatedProducts() as $currentProduct) {
            if ($product->getId() === $currentProduct->getId()) {
                continue;
            }

            $association = $this->getProductAssociationByType($currentProduct, $associationType);
            $association->addAssociatedProduct($product);
        }
    }
}
