<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Domain;

use App\Entity\Article;
use Behat\Behat\Context\Context;
use Sylius\Component\Customer\Model\CustomerInterface;
use App\Entity\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class NotificationContext implements Context
{
    /**
     * @var RepositoryInterface
     */
    private $notificationRepository;

    /**
     */
    public function __construct(RepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @Then there is (also )a notification sent to :customer
     */
    public function thereIsNotificationSentToCustomer(CustomerInterface $customer)
    {
        $this->assertNotificationExists(['recipient' => $customer]);
    }

    /**
     * @Then there is (also )a notification sent to :customer for article :article
     */
    public function thereIsNotificationSentToCustomerForArticle(CustomerInterface $customer, Article $article)
    {
        $this->assertNotificationExists([
            'recipient' => $customer,
            'article' => $article,
        ]);
    }

    /**
     * @Then there is (also )a notification sent to :customer for product :product
     */
    public function thereIsNotificationSentToCustomerForProduct(CustomerInterface $customer, ProductInterface $product)
    {
        $this->assertNotificationExists([
            'recipient' => $customer,
            'product' => $product,
        ]);
    }

    /**
     */
    private function assertNotificationExists(array $criteria): void
    {
        $notification = $this->notificationRepository->findOneBy($criteria);

        Assert::notNull($notification, 'No notification found');
    }
}
