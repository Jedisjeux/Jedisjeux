<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Ui\Backend;

use App\Tests\Behat\NotificationType;
use App\Tests\Behat\Page\Backend\ProductFile\IndexPage;
use App\Tests\Behat\Page\Backend\ProductFile\UpdatePage;
use App\Entity\ProductFile;
use Behat\Behat\Context\Context;
use Monofony\Bundle\CoreBundle\Tests\Behat\Service\NotificationCheckerInterface;
use Webmozart\Assert\Assert;

class ManagingProductFilesContext implements Context
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

    /**
     * @param IndexPage                    $indexPage
     * @param UpdatePage                   $updatePage
     * @param NotificationCheckerInterface $notificationChecker
     */
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
     * @When I want to browse product files
     */
    public function iWantToBrowseProductFiles(): void
    {
        $this->indexPage->open();
    }

    /**
     * @Given /^I want to edit (this product file)$/
     */
    public function iWantToEditTheProductFile(ProductFile $productFile)
    {
        $this->updatePage->open(['id' => $productFile->getId()]);
    }

    /**
     * @When I attach the :path file
     */
    public function iAttachFile($path)
    {
        $this->updatePage->attachFile($path);
    }

    /**
     * @When I change its title to :title
     * @When I remove its title
     */
    public function iChangeItsTitle(string $title = null)
    {
        $this->updatePage->specifyTitle($title);
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
     * @When I accept this file
     */
    public function iAcceptFile()
    {
        $this->updatePage->accept();
    }

    /**
     * @When I reject this file
     */
    public function iRejectFile()
    {
        $this->updatePage->reject();
    }

    /**
     * @When I delete product file :title
     */
    public function iDeleteFile(string $title)
    {
        $this->indexPage->deleteResourceOnPage([
            'title' => $title,
        ]);
    }

    /**
     * @Then the file :title should appear in the website
     * @Then I should see the file :title in the list
     */
    public function theProductFileShould(string $title)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['title' => $title]));
    }

    /**
     * @Then I should be notified that the :elementName is required
     */
    public function iShouldBeNotifiedThatElementNameIsRequired($elementName)
    {
        Assert::same($this->updatePage->getValidationMessage($elementName), 'This value should not be blank.');
    }

    /**
     * @Then I should be notified that the file is not a valid file
     */
    public function iShouldBeNotifiedThatFileIsNotAValidFile()
    {
        Assert::contains($this->updatePage->getFileError(), 'The mime type of the file is invalid');
    }

    /**
     * @Then this product file should not be added
     */
    public function thisProductFileShouldNotBeAdded()
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
     * @Then /^(this product file) should have "([^"]+)" status$/
     */
    public function thisProductFileWithTitleShouldHaveStatus(ProductFile $productFile, $status)
    {
        $this->indexPage->open();

        $status = ucfirst($status);

        Assert::true($this->indexPage->isSingleResourceOnPage([
            'product' => $productFile->getProduct()->getName(),
            'status' => $status,
        ]));
    }

    /**
     * @Then there should not be product file :title anymore
     */
    public function thereShouldBeNoProductFileAnymore(string $title)
    {
        Assert::false($this->indexPage->isSingleResourceOnPage([
            'title' => $title,
        ]));
    }

    /**
     * @Then /^(this product file) should be titled "([^"]+)"$/
     * @Then /^(this product file) should still be titled "([^"]+)"$/
     */
    public function thisProductFileShouldBeTitled(ProductFile $productFile, $title)
    {
        $this->iWantToEditTheProductFile($productFile);

        $this->assertElementValue('title', $title);
    }

    /**
     * @param string $element
     * @param string $value
     */
    private function assertElementValue($element, $value)
    {
        Assert::true(
            $this->updatePage->hasResourceValues([$element => $value]),
            sprintf('Product file should have %s with %s value.', $element, $value)
        );
    }
}
