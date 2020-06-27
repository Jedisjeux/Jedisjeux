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

use App\Fixture\Factory\ProductReviewExampleFactory;
use Behat\Behat\Context\Context;
use Monofony\Bundle\CoreBundle\Tests\Behat\Service\SharedStorageInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Review\Model\ReviewInterface;

class ProductReviewContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * @var ProductReviewExampleFactory
     */
    private $productReviewFactory;

    /**
     * @var RepositoryInterface
     */
    protected $productReviewRepository;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        ProductReviewExampleFactory $productReviewFactory,
        RepositoryInterface $productReviewRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->productReviewFactory = $productReviewFactory;
        $this->productReviewRepository = $productReviewRepository;
    }

    /**
     * @Given /^(this product) has one review from (customer "[^"]+")$/
     * @Given /^I wrote a review on (this product)$/
     *
     * @param ProductInterface  $product
     * @param CustomerInterface $customer
     */
    public function productHasAReview(ProductInterface $product, CustomerInterface $customer = null)
    {
        if (null === $customer) {
            $customer = $this->sharedStorage->get('customer');
        }

        /** @var ReviewInterface $review */
        $review = $this->productReviewFactory->create([
            'product' => $product,
            'author' => $customer,
        ]);

        $this->productReviewRepository->add($review);
        $this->sharedStorage->set('product_review', $review);
    }

    /**
     * @Given /^(this product) has(?:| also) a review titled "([^"]+)" and rated (\d+) added by (customer "[^"]+")(?:|, created (\d+) days ago)$/
     */
    public function thisProductHasAReviewTitledAndRatedAddedByCustomer(
        ProductInterface $product,
        $title,
        $rating,
        CustomerInterface $customer,
        $daysSinceCreation = null
    ) {
        /** @var ReviewInterface $review */
        $review = $this->productReviewFactory->create([
            'product' => $product,
            'title' => $title,
            'rating' => $rating,
            'author' => $customer,
        ]);

        if (null !== $daysSinceCreation) {
            $review->setCreatedAt(new \DateTime('-'.$daysSinceCreation.' days'));
        }

        $this->productReviewRepository->add($review);
        $this->sharedStorage->set('product_review', $review);
    }

    /**
     * @Given /^(this product) has been rated with a (\d+) by (customer "[^"]+")(?:|, created (\d+) days ago)$/
     */
    public function customerHasRatedThisProduct(
        ProductInterface $product,
        $rating,
        CustomerInterface $customer,
        $daysSinceCreation = null
    ) {
        /** @var ReviewInterface $review */
        $review = $this->productReviewFactory->create([
            'product' => $product,
            'title' => null,
            'comment' => null,
            'rating' => $rating,
            'author' => $customer,
        ]);

        if (null !== $daysSinceCreation) {
            $review->setCreatedAt(new \DateTime('-'.$daysSinceCreation.' days'));
        }

        $this->productReviewRepository->add($review);
        $this->sharedStorage->set('product_review', $review);
    }
}
