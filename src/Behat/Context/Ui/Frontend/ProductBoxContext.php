<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Ui\Frontend;

use App\Behat\NotificationType;
use App\Behat\Page\Frontend\ProductBox\CreatePage;
use App\Behat\Service\NotificationCheckerInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;

class ProductBoxContext implements Context
{
    /**
     * @var CreatePage
     */
    private $createPage;

    /**
     * @var NotificationCheckerInterface
     */
    private $notificationChecker;

    /**
     * @param CreatePage                   $createPage
     * @param NotificationCheckerInterface $notificationChecker
     */
    public function __construct(CreatePage $createPage, NotificationCheckerInterface $notificationChecker)
    {
        $this->createPage = $createPage;
        $this->notificationChecker = $notificationChecker;
    }

    /**
     * @When /^I want to add a product box image to (this product)$/
     */
    public function iWantToAddProductBox(ProductInterface $product): void
    {
        $this->createPage->open(['slug' => $product->getSlug()]);
    }

    /**
     * @When I attach the :path image
     */
    public function iAttachImage($path)
    {
        $this->createPage->attachImage($path);
    }

    /**
     * @When I add it
     * @When I try to add it
     */
    public function iAddIt()
    {
        $this->createPage->create();
    }

    /**
     * @Then I should be notified that my image is waiting for the acceptation
     */
    public function iShouldBeNotifiedThatMyImageIsWaitingForTheAcceptation()
    {
        $this->notificationChecker->checkNotification(
            'Your image is waiting for the acceptation.',
            NotificationType::success()
        );
    }
}
