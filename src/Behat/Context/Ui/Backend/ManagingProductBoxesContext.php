<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Ui\Backend;

use App\Behat\NotificationType;
use App\Behat\Page\Backend\ProductBox\CreatePage;
use App\Behat\Page\Backend\ProductBox\IndexPage;
use App\Behat\Page\Backend\ProductBox\UpdatePage;
use App\Behat\Service\NotificationCheckerInterface;
use App\Entity\ProductBox;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;
use Webmozart\Assert\Assert;

class ManagingProductBoxesContext implements Context
{
    /**
     * @var CreatePage
     */
    private $createPage;

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

    /**
     * @param CreatePage                   $createPage
     * @param IndexPage                    $indexPage
     * @param UpdatePage                   $updatePage
     * @param NotificationCheckerInterface $notificationChecker
     */
    public function __construct(
        CreatePage $createPage,
        IndexPage $indexPage,
        UpdatePage $updatePage,
        NotificationCheckerInterface $notificationChecker
    ) {
        $this->createPage = $createPage;
        $this->indexPage = $indexPage;
        $this->updatePage = $updatePage;
        $this->notificationChecker = $notificationChecker;
    }

    /**
     * @When I want to create a new product box
     */
    public function iWantToAddProductBox(): void
    {
        $this->createPage->open();
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
    public function iWantToEditTheArticle(ProductBox $productBox)
    {
        $this->updatePage->open(['id' => $productBox->getId()]);
    }

    /**
     * @When I attach the :path image
     */
    public function iAttachImage($path)
    {
        $this->createPage->attachImage($path);
    }

    /**
     * @When I specify its product as :product
     * @When I do not specify its product
     */
    public function iSpecifyItsProductAs(?ProductInterface $product)
    {
        $this->createPage->specifyProduct($product);
    }

    /**
     * @When I specify its height as :height
     * @When I do not specify its height
     */
    public function iSpecifyItsHeightAs(?int $height = null)
    {
        $this->createPage->specifyHeight($height);
    }

    /**
     * @When /^I change its height as (\d+)$/
     */
    public function iChangeItsPlayedAtAs($height)
    {
        $this->updatePage->changeHeight($height);
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
        Assert::same($this->createPage->getValidationMessage($elementName), 'This value should not be blank.');
    }

    /**
     * @Then I should be notified that the file is not a valid image
     */
    public function iShouldBeNotifiedThatFileIsNotAValidImage()
    {
        Assert::same($this->createPage->getValidationMessage('image_file'), 'This file is not a valid image.');
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
     * @Then this product box should be :height high
     */
    public function thisProductBoxShouldBePlayedAt($height)
    {
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
