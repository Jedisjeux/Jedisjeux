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

namespace App\Behat\Context\Setup;

use App\Behat\Service\SharedStorageInterface;
use App\Entity\ProductFile;
use App\Fixture\Factory\ProductFileExampleFixture;
use Behat\Behat\Context\Context;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ProductFileContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var ProductFileExampleFixture
     */
    private $productFileFactory;

    /**
     * @var RepositoryInterface
     */
    private $productFileRepository;

    /**
     * @param SharedStorageInterface    $sharedStorage
     * @param ProductFileExampleFixture $productFileFactory
     * @param RepositoryInterface       $productFileRepository
     */
    public function __construct(SharedStorageInterface $sharedStorage, ProductFileExampleFixture $productFileFactory, RepositoryInterface $productFileRepository)
    {
        $this->sharedStorage = $sharedStorage;
        $this->productFileFactory = $productFileFactory;
        $this->productFileRepository = $productFileRepository;
    }

    /**
     * @Given /^(this product) has(?:| also) a file titled "([^"]+)"$/
     * @Given /^(this product) has(?:| also) a file titled "([^"]+)" with "([^"]+)" status$/
     * @Given /^(this product) has(?:| also) a file titled "([^"]+)" with "([^"]+)" status added by (customer "[^"]+")$/
     */
    public function thisProductHasAFileWithStatus(
        ProductInterface $product,
        string $title,
        string $status = null,
        CustomerInterface $customer = null
    ): void {
        $this->createProductFile([
            'product' => $product,
            'title' => $title,
            'status' => $status ?? ProductFile::STATUS_ACCEPTED,
            'author' => $customer,
        ]);
    }

    /**
     * @Given /^(this product) has(?:| also) a file titled "([^"]+)" added by (customer "[^"]+")$/
     */
    public function productHasFileAddedByCustomer(
        ProductInterface $product,
        string $title,
        CustomerInterface $customer
    ) {
        $this->createProductFile([
            'author' => $customer,
            'product' => $product,
            'title' => $title,
            'status' => ProductFile::STATUS_ACCEPTED,
        ]);
    }

    /**
     * @param array $options
     */
    public function createProductFile(array $options): void
    {
        /** @var ProductFile $productFile */
        $productFile = $this->productFileFactory->create($options);

        $this->productFileRepository->add($productFile);
        $this->sharedStorage->set('product_file', $productFile);
    }
}
