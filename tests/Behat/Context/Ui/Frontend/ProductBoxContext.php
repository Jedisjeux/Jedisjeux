<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Ui\Frontend;

use App\Tests\Behat\NotificationType;
use App\Tests\Behat\Page\Frontend\ProductBox\CreatePage;
use App\Tests\Behat\Service\NotificationCheckerInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;
use Webmozart\Assert\Assert;

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
     * @When /^I specify its height as (\d+)$/
     * @When I do not specify its height
     */
    public function iSpecifyItsHeightAs(?int $height = null)
    {
        $this->createPage->specifyHeight($height);
    }

    /**
     * @When I add it
     * @When I try to add it
     */
    public function iAddIt()
    {
        $this->createPage->submit();
    }

    /**
     * @Then I should be notified that my image is waiting for the acceptation
     */
    public function iShouldBeNotifiedThatMyImageIsWaitingForTheAcceptation()
    {
        $this->notificationChecker->checkNotification(
            'Your game box is waiting for the acceptation.',
            NotificationType::success()
        );
    }

    /**
     * @Then I should be notified that the :elementName is required
     */
    public function iShouldBeNotifiedThatCommentIsRequired($elementName)
    {
        Assert::true($this->createPage->checkValidationMessageFor($elementName, 'This value should not be blank.'));
    }
}
