<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Context\Ui\Frontend;

use Monofony\Bridge\Behat\NotificationType;
use App\Tests\Behat\Page\Frontend\ProductFile\CreatePage;
use App\Tests\Behat\Page\Frontend\ProductFile\IndexPage;
use Behat\Behat\Context\Context;
use Monofony\Bridge\Behat\Service\NotificationCheckerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Webmozart\Assert\Assert;

final class ProductFileContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @var CreatePage
     */
    private $createPage;

    /**
     * @var NotificationCheckerInterface
     */
    private $notificationChecker;

    public function __construct(
        IndexPage $indexPage,
        CreatePage $createPage,
        NotificationCheckerInterface $notificationChecker
    ) {
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
        $this->notificationChecker = $notificationChecker;
    }

    /**
     * @When /^I check (this product)'s files$/
     */
    public function iCheckThisProductFiles(ProductInterface $product)
    {
        $this->indexPage->open(['slug' => $product->getSlug()]);
    }

    /**
     * @When /^I want to add a file to (this product)$/
     */
    public function iWantToAddProductFile(ProductInterface $product): void
    {
        $this->createPage->open(['slug' => $product->getSlug()]);
    }

    /**
     * @When I attach the :path file
     */
    public function iAttachFile($path)
    {
        $this->createPage->attachFile($path);
    }

    /**
     * @When I specify its title as :title
     */
    public function iSpecifyItsTitleAs($title = null)
    {
        $this->createPage->specifyTitle($title);
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
     * @Then /^I should see (\d+) product files in the list$/
     */
    public function iShouldSeeNumberOfProductFilesInTheList($count)
    {
        Assert::same($this->indexPage->countFiles(), (int) $count);
    }

    /**
     * @Then /^I should be notified that there are no files$/
     */
    public function iShouldBeNotifiedThatThereAreNoFiles()
    {
        Assert::true($this->indexPage->hasNoFilesMessage());
    }

    /**
     * @Then I should be notified that my file is waiting for the acceptation
     */
    public function iShouldBeNotifiedThatMyFileIsWaitingForTheAcceptation()
    {
        $this->notificationChecker->checkNotification(
            'Your file is waiting for the acceptation.',
            NotificationType::success()
        );
    }
}
