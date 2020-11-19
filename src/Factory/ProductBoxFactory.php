<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\ProductBox;
use App\Entity\ProductInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class ProductBoxFactory implements FactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var CustomerContextInterface
     */
    private $customerContext;

    public function __construct(FactoryInterface $factory, CustomerContextInterface $customerContext)
    {
        $this->factory = $factory;
        $this->customerContext = $customerContext;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew(): ProductBox
    {
        /** @var ProductBox $productBox */
        $productBox = $this->factory->createNew();
        $productBox->setAuthor($this->customerContext->getCustomer());

        return $productBox;
    }

    /**
     * @return ProductBox
     */
    public function createForProduct(ProductInterface $product)
    {
        $productBox = $this->createNew();
        $productBox->setProduct($product);

        return $productBox;
    }
}
