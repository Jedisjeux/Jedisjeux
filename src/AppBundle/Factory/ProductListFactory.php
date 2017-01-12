<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Factory;

use AppBundle\Entity\ProductList;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductListFactory implements FactoryInterface
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var CustomerContextInterface
     */
    protected $customerContext;

    /**
     * @param string $className
     */
    public function __construct($className, CustomerContextInterface $customerContext)
    {
        $this->className = $className;
        $this->customerContext = $customerContext;
    }

    /**
     * @return ProductList
     */
    public function createNew()
    {
        /** @var ProductList $productList */
        $productList = new $this->className;
        $productList
            ->setOwner($this->customerContext->getCustomer());

        return $productList;
    }

    /**
     * @param $code
     *
     * @return ProductList
     */
    public function createForCode($code)
    {
        $productList = $this->createNew();

        $productList
            ->setCode($code)
            ->setName('app.ui.'.$code);

        return $productList;
    }
}