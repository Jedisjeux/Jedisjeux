<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\ProductBox;
use App\Entity\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class ProductBoxFactory implements FactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew(): ProductBox
    {
        return $this->factory->createNew();
    }

    /**
     * @param ProductInterface $product
     *
     * @return ProductBox
     */
    public function createForProduct(ProductInterface $product)
    {
        $productBox = $this->createNew();
        $productBox->setProduct($product);

        return $productBox;
    }

}
