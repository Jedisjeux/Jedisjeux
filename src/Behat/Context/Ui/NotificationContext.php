<?php
/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Ui;

use App\Entity\Article;
use Behat\Behat\Context\Context;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

class NotificationContext implements Context
{
    /**
     * @var RepositoryInterface
     */
    private $notificationRepository;

    /**
     * @param RepositoryInterface $notificationRepository
     */
    public function __construct(RepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @Then there is (also )a notification sent to :customer for article :article
     */
    public function thereIsNotificationSentToCustomerForArticle(CustomerInterface $customer, Article $article)
    {
        $notification = $this->notificationRepository->findOneBy([
            'recipient' => $customer,
            'article' => $article,
        ]);

        Assert::notNull($notification, 'No notification found');
    }

    /**
     * @Then there is (also )a notification sent to :customer for product :product
     */
    public function thereIsNotificationSentToCustomerForProduct(CustomerInterface $customer, ProductInterface $product)
    {
        $notification = $this->notificationRepository->findOneBy([
            'recipient' => $customer,
            'product' => $product,
        ]);

        Assert::notNull($notification, 'No notification found');
    }
}
