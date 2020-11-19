<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Ui\Backend;

use Monofony\Bridge\Behat\NotificationType;
use App\Tests\Behat\Page\Backend\ProductBox\IndexPage;
use App\Tests\Behat\Page\Backend\ProductBox\UpdatePage;
use App\Entity\ProductBox;
use Behat\Behat\Context\Context;
use Monofony\Bridge\Behat\Service\NotificationCheckerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Webmozart\Assert\Assert;

final class ManagingProductBoxesContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @var UpdatePage
     */
    private $updatePage;

    /**
     * @var NotificationCheckerInterface
     */
    private $notificationChecker;

    public function __construct(
        IndexPage $indexPage,
        UpdatePage $updatePage,
        NotificationCheckerInterface $notificationChecker
    ) {
        $this->indexPage = $indexPage;
        $this->updatePage = $updatePage;
        $this->notificationChecker = $notificationChecker;
    }

    /**
     * @When I want to browse product boxes
     */
    public function iWantToBrowseProductBoxes(): void
    {
        $this->indexPage->open();
    }

    /**
     * @Given /^I want to edit (this product box)$/
     */
    public function iWantToEditTheProductBox(ProductBox $productBox)
    {
        $this->updatePage->open(['id' => $productBox->getId()]);
    }

    /**
     * @When I attach the :path image
     */
    public function iAttachImage($path)
    {
        $this->updatePage->attachImage($path);
    }

    /**
     * @When I change its height to :height
     * @When I remove its height
     */
    public function iChangeItsHeightTo(?int $height = null)
    {
        $this->updatePage->changeHeight($height);
    }

    /**
     * @When I save my changes
     * @When I try to save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @When I accept this box
     */
    public function iAcceptBox()
    {
        $this->updatePage->accept();
    }

    /**
     * @When I reject this box
     */
    public function iRejectBox()
    {
        $this->updatePage->reject();
    }

    /**
     * @When I delete product box of :product
     */
    public function iDeleteBox(ProductInterface $product)
    {
        $productName = $product->getName();

        $this->indexPage->deleteResourceOnPage([
            'product' => $productName,
        ]);
    }

    /**
     * @Then the box for product :product should appear in the website
     * @Then I should see the box for product :product in the list
     */
    public function theProductBoxShould(ProductInterface $product)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['product' => $product->getName()]));
    }

    /**
     * @Then I should be notified that the :elementName is required
     */
    public function iShouldBeNotifiedThatCommentIsRequired($elementName)
    {
        Assert::same($this->updatePage->getValidationMessage($elementName), 'This value should not be blank.');
    }

    /**
     * @Then I should be notified that the file is not a valid image
     */
    public function iShouldBeNotifiedThatFileIsNotAValidImage()
    {
        Assert::same($this->updatePage->getImageFileError(), 'This file is not a valid image.');
    }

    /**
     * @Then this product box should not be added
     */
    public function thisProductBoxShouldNotBeAdded()
    {
        $this->indexPage->open();

        Assert::same($this->indexPage->countItems(), 0);
    }

    /**
     * @Then I should be notified that it has been successfully accepted
     */
    public function iShouldBeNotifiedThatItHasBeenSuccessfullyAccepted()
    {
        $this->notificationChecker->checkNotification('has been successfully accepted.', NotificationType::success());
    }

    /**
     * @Then I should be notified that it has been successfully rejected
     */
    public function iShouldBeNotifiedThatItHasBeenSuccessfullyRejected()
    {
        $this->notificationChecker->checkNotification('has been successfully rejected.', NotificationType::success());
    }

    /**
     * @Then /^(this product box) should have "([^"]+)" status$/
     */
    public function thisArticleWithTitleShouldHaveStatus(ProductBox $productBox, $status)
    {
        $this->indexPage->open();

        $status = ucfirst($status);

        Assert::true($this->indexPage->isSingleResourceOnPage([
            'product' => $productBox->getProduct()->getName(),
            'status' => $status,
        ]));
    }

    /**
     * @Then there should not be product box of :product anymore
     */
    public function thereShouldBeNoGamePlayWitAnymore(ProductInterface $product)
    {
        Assert::false($this->indexPage->isSingleResourceOnPage([
            'product' => $product->getName(),
        ]));
    }

    /**
     * @Then /^(this product box) should be (\d+) high$/
     * @Then /^(this product box) should still be (\d+) high$/
     */
    public function thisProductBoxShouldBePlayedAt(ProductBox $productBox, $height)
    {
        $this->iWantToEditTheProductBox($productBox);

        $this->assertElementValue('height', $height);
    }

    /**
     * @param string $element
     * @param string $value
     */
    private function assertElementValue($element, $value)
    {
        Assert::true(
            $this->updatePage->hasResourceValues([$element => $value]),
            sprintf('Product box should have %s with %s value.', $element, $value)
        );
    }
}
