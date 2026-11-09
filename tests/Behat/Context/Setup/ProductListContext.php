<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Setup;

use App\Entity\Customer;
use App\Entity\ProductList;
use App\Entity\ProductListItem;
use App\Entity\User;
use App\Fixture\Factory\ProductExampleFactory;
use App\Fixture\Factory\ProductListExampleFactory;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;
use Doctrine\Persistence\ObjectManager;
use App\Tests\Behat\Bridge\Service\SharedStorageInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

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
     * @var FactoryInterface
     */
    private $productListItemFactory;

    /**
     * @var ProductExampleFactory
     */
    private $productFactory;

    /**
     * @var RepositoryInterface
     */
    protected $productListRepository;

    /**
     * @var RepositoryInterface
     */
    protected $productRepository;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @param SharedStorageInterface    $sharedStorage
     * @param ProductListExampleFactory $productListFactory
     * @param FactoryInterface          $productListItemFactory
     * @param ProductExampleFactory     $productFactory
     * @param RepositoryInterface       $productListRepository
     * @param RepositoryInterface       $productRepository
     * @param ObjectManager             $objectManager
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ProductListExampleFactory $productListFactory,
        FactoryInterface $productListItemFactory,
        ProductExampleFactory $productFactory,
        RepositoryInterface $productListRepository,
        RepositoryInterface $productRepository,
        ObjectManager $objectManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->productListFactory = $productListFactory;
        $this->productListItemFactory = $productListItemFactory;
        $this->productFactory = $productFactory;
        $this->productListRepository = $productListRepository;
        $this->productRepository = $productRepository;
        $this->objectManager = $objectManager;
    }

    /**
     * @Given /^(this customer) has(?:| also) a product list "([^"]+)"$/
     */
    public function thisCustomerHasAProductList(Customer $customer, string $name)
    {
        /** @var ProductList $productList */
        $productList = $this->productListFactory->create([
            'owner' => $customer,
            'name' => $name,
            'products' => [],
        ]);

        $this->productListRepository->add($productList);
        $this->sharedStorage->set('product_list', $productList);
    }

    /**
     * @Given /^(this user) has a game library$/
     */
    public function userHasAGameLibrary(User $user)
    {
        /** @var ProductList $productList */
        $productList = $this->productListFactory->create([
            'code' => ProductList::CODE_GAME_LIBRARY,
            'owner' => $user->getCustomer(),
            'products' => [],
        ]);

        $this->productListRepository->add($productList);
        $this->sharedStorage->set('product_list', $productList);
    }

    /**
     * @Given /^(this user) has "([^"]+)" in his game library$/
     */
    public function userHasGameInGameLibrary(User $user, $productName)
    {
        $product = $this->productFactory->create(['name' => $productName]);
        $this->productRepository->add($product);

        /** @var ProductList $productList */
        $productList = $this->sharedStorage->get('product_list');

        /** @var ProductListItem $productListItem */
        $productListItem = $this->productListItemFactory->createNew();
        $productListItem->setProduct($product);

        $productList->addItem($productListItem);
        $this->objectManager->flush();
    }

    /**
     * @Given /^(this product list) contains ("[^"]+" product)$/
     * @Given /^(this product list) also contains ("[^"]+" product)$/
     */
    public function thisProductListContainsProduct(ProductList $productList, ProductInterface $product): void
    {
        /** @var ProductListItem $productListItem */
        $productListItem = $this->productListItemFactory->createNew();
        $productListItem->setProduct($product);

        $productList->addItem($productListItem);
        $this->objectManager->flush();
    }

    /**
     * @Given /^my game library(?:| also) contains "([^"]+)"$/
     */
    public function myGameLibraryContains(string $productName): void
    {
        /** @var Customer $customer */
        $customer = $this->sharedStorage->get('customer');

        /** @var ProductList|null $gameLibrary */
        $gameLibrary = $this->productListRepository->findOneBy([
            'owner' => $customer,
            'code' => ProductList::CODE_GAME_LIBRARY,
        ]);

        if (null === $gameLibrary) {
            /** @var ProductList $gameLibrary */
            $gameLibrary = $this->productListFactory->create([
                'code' => ProductList::CODE_GAME_LIBRARY,
                'owner' => $customer,
                'products' => [],
            ]);
            $this->productListRepository->add($gameLibrary);
        }

        $product = $this->productFactory->create(['name' => $productName]);
        $this->productRepository->add($product);

        /** @var ProductListItem $productListItem */
        $productListItem = $this->productListItemFactory->createNew();
        $productListItem->setProduct($product);

        $gameLibrary->addItem($productListItem);
        $this->objectManager->flush();
    }
}
