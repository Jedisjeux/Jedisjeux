<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Factory;

use App\Entity\CustomerInterface;
use App\Entity\ProductFile;
use App\Entity\ProductInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Webmozart\Assert\Assert;

class ProductFileFactory implements FactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var CustomerContextInterface
     */
    private $customerContext;

    /**
     * @param FactoryInterface         $factory
     * @param CustomerContextInterface $customerContext
     */
    public function __construct(FactoryInterface $factory, CustomerContextInterface $customerContext)
    {
        $this->factory = $factory;
        $this->customerContext = $customerContext;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew(): ProductFile
    {
        /** @var ProductFile $productFile */
        $productFile = $this->factory->createNew();

        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();
        Assert::nullOrIsInstanceOf($customer, CustomerInterface::class);

        $productFile->setAuthor($customer);

        return $productFile;
    }

    /**
     * @param ProductInterface $product
     *
     * @return ProductFile
     */
    public function createForProduct(ProductInterface $product): ProductFile
    {
        $productFile = $this->createNew();
        $productFile->setProduct($product);

        return $productFile;
    }
}
