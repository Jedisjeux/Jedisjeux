<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Product\Model\ProductAssociation as BaseProductAssociation;
use Sylius\Component\Product\Model\ProductInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_association")
 *
 * todo remove after sylius upgrade (here we are in alpha-1)
 */
class ProductAssociation extends BaseProductAssociation
{
    /**
     * {@inheritdoc}
     */
    public function getAssociatedProducts()
    {
        return $this->getAssociatedObjects();
    }

    /**
     * {@inheritdoc}
     */
    public function hasAssociatedProduct(ProductInterface $product)
    {
        return $this->associatedObjects->contains($product);
    }

    /**
     * {@inheritdoc}
     */
    public function addAssociatedProduct(ProductInterface $product)
    {
        if (!$this->hasAssociatedProduct($product)) {
            $this->associatedObjects->add($product);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAssociatedProduct(ProductInterface $product)
    {
        if ($this->hasAssociatedProduct($product)) {
            $this->associatedObjects->removeElement($product);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clearAssociatedProducts()
    {
        $this->associatedObjects->clear();
    }
}
