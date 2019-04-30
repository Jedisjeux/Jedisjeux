<?php
/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Domain;

use App\Entity\Article;
use Behat\Behat\Context\Context;
use Sylius\Component\Customer\Model\CustomerInterface;
use App\Entity\ProductInterface;
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
     * @Then customer :customer should have received a notification
     */
    public function customerShouldHaveNotification(CustomerInterface $customer)
    {
        $this->assertNotificationExists(['recipient' => $customer]);
    }

    /**
     * @Then customer :customer should (also )have received a notification for article :article
     * @Then a notification should be sent to :customer for :article
     */
    public function customerShouldHaveNotificationForArticle(CustomerInterface $customer, Article $article)
    {
        $this->assertNotificationExists([
            'recipient' => $customer,
            'article' => $article,
        ]);
    }

    /**
     * @Then customer :customer should (also )have received a notification for product :product
     */
    public function customerShouldHaveNotificationForProduct(CustomerInterface $customer, ProductInterface $product)
    {
        $this->assertNotificationExists([
            'recipient' => $customer,
            'product' => $product,
        ]);
    }

    /**
     * @param array $criteria
     */
    private function assertNotificationExists(array $criteria): void
    {
        $notification = $this->notificationRepository->findOneBy($criteria);

        Assert::notNull($notification, 'No notification found');
    }
}
