<?php
/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Setup;

use App\Behat\Service\SharedStorageInterface;
use App\Entity\ProductBox;
use App\Entity\ProductInterface;
use App\Fixture\Factory\ProductBoxExampleFactory;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ProductBoxContext implements Context
{
    /**
     * @var ProductBoxExampleFactory
     */
    private $productBoxFactory;

    /**
     * @var RepositoryInterface
     */
    private $productBoxRepository;

    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @param ProductBoxExampleFactory $productBoxFactory
     * @param RepositoryInterface      $productBoxRepository
     * @param SharedStorageInterface   $sharedStorage
     */
    public function __construct(
        ProductBoxExampleFactory $productBoxFactory,
        RepositoryInterface $productBoxRepository,
        SharedStorageInterface $sharedStorage
    ) {
        $this->productBoxFactory = $productBoxFactory;
        $this->productBoxRepository = $productBoxRepository;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Given /^(this product) has(?:| also) a box$/
     * @Given /^(this product) has(?:| also) a box with "([^"]+)" status$/
     */
    public function thisProductHasABoxWithStatus(
        ProductInterface $product,
        string $status = null
    ) {
        /** @var ProductBox $productBox */
        $productBox = $this->productBoxFactory->create([
            'product' => $product,
            'status' => $status ?? ProductBox::STATUS_ACCEPTED,
            'author' => null,
        ]);

        $this->productBoxRepository->add($productBox);
        $this->sharedStorage->set('product_box', $productBox);
    }
}
