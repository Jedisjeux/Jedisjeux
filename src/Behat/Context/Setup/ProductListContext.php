<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Setup;

use App\Behat\Service\SharedStorageInterface;
use App\Entity\ProductList;
use App\Fixture\Factory\ProductListExampleFactory;
use Behat\Behat\Context\Context;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductListContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * @var ProductListExampleFactory
     */
    private $productListFactory;

    /**
     * @var RepositoryInterface
     */
    protected $productListRepository;

    /**
     * @param SharedStorageInterface    $sharedStorage
     * @param ProductListExampleFactory $productListFactory
     * @param RepositoryInterface       $productListRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ProductListExampleFactory $productListFactory,
        RepositoryInterface $productListRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->productListFactory = $productListFactory;
        $this->productListRepository = $productListRepository;
    }

    /**
     * @Given /^(this customer) has(?:| also) a product list "([^"]+)"$/
     */
    public function thisCustomerHasAProductList(
        CustomerInterface $customer,
        $name
    ) {
        /** @var ProductList $productList */
        $productList = $this->productListFactory->create([
            'owner' => $customer,
            'name' => $name,
        ]);

        $this->productListRepository->add($productList);
        $this->sharedStorage->set('product_list', $productList);
    }
}
