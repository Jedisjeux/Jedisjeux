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
use App\Entity\CustomerInterface;
use App\Entity\ProductInterface;
use App\Entity\ProductSubscription;
use App\Fixture\Factory\ProductSubscriptionExampleFactory;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ProductSubscriptionContext implements Context
{
    /** @var ProductSubscriptionExampleFactory */
    private $productSubscriptionFactory;

    /** @var RepositoryInterface */
    private $productSubscriptionRepository;

    /** @var SharedStorageInterface */
    private $sharedStorage;

    /**
     * @param ProductSubscriptionExampleFactory $productSubscriptionFactory
     * @param RepositoryInterface               $productSubscriptionRepository
     * @param SharedStorageInterface            $sharedStorage
     */
    public function __construct(
        ProductSubscriptionExampleFactory $productSubscriptionFactory,
        RepositoryInterface $productSubscriptionRepository,
        SharedStorageInterface $sharedStorage
    ) {
        $this->productSubscriptionFactory = $productSubscriptionFactory;
        $this->productSubscriptionRepository = $productSubscriptionRepository;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Given /^(customer "[^"]+") has subscribed to (this product) to be notified about new (articles)$/
     */
    public function customerHasSubscribedThisProduct(
        CustomerInterface $customer,
        ProductInterface $product,
        string $option
    ) {
        $option = sprintf('%s_%s', 'follow', $option);

        $this->createProductSubscription([
            'subject' => $product,
            'subscriber' => $customer,
            'options' => [$option],
        ]);
    }

    private function createProductSubscription(array $options): void
    {
        /** @var ProductSubscription $subscription */
        $subscription = $this->productSubscriptionFactory->create($options);

        $this->productSubscriptionRepository->add($subscription);
        $this->sharedStorage->set('product_subscription', $subscription);
    }
}
