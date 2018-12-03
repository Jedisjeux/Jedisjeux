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
use App\Entity\ProductVideo;
use App\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ProductVideoContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * @var ExampleFactoryInterface
     */
    private $productVideoFactory;

    /**
     * @var RepositoryInterface
     */
    private $productVideoRepository;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param ExampleFactoryInterface $productVideoFactory
     * @param RepositoryInterface $productVideoRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ExampleFactoryInterface $productVideoFactory,
        RepositoryInterface $productVideoRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->productVideoFactory = $productVideoFactory;
        $this->productVideoRepository = $productVideoRepository;
    }

    /**
     * @Given /^(this product) has(?:| also) a video titled "([^"]+)"$/
     */
    public function thisProductHasAVideoTitled(
        ProductInterface $product,
        $title
    ) {
        /** @var ProductVideo $productVideo */
        $productVideo = $this->productVideoFactory->create([
            'product' => $product,
            'title' => $title,
        ]);

        $this->productVideoRepository->add($productVideo);
        $this->sharedStorage->set('product_video', $productVideo);
    }
}
