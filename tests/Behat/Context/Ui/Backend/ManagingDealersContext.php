<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Ui\Backend;

use App\Tests\Behat\Page\Backend\Dealer\IndexPage;
use App\Tests\Behat\Page\Backend\Dealer\UpdatePage;
use App\Tests\Behat\Page\Backend\Dealer\CreatePage;
use App\Entity\Dealer;
use Behat\Behat\Context\Context;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Monofony\Bridge\Behat\Service\Resolver\CurrentPageResolverInterface;
use Webmozart\Assert\Assert;

final class ManagingDealersContext implements Context
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
     * @var UpdatePage
     */
    private $updatePage;

    /**
     * @var CurrentPageResolverInterface
     */
    private $currentPageResolver;

    public function __construct(
        IndexPage $indexPage,
        CreatePage $createPage,
        UpdatePage $updatePage,
        CurrentPageResolverInterface $currentPageResolver)
    {
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
        $this->currentPageResolver = $currentPageResolver;
    }

    /**
     * @Given I want to create a new dealer
     */
    public function iWantToCreateANewDealer()
    {
        $this->createPage->open();
    }

    /**
     * @When I want to browse dealers
     */
    public function iWantToBrowseDealers()
    {
        $this->indexPage->open();
    }

    /**
     * @Given I want to edit :dealer dealer
     */
    public function iWantToEditTheDealer(Dealer $dealer)
    {
        $this->updatePage->open(['id' => $dealer->getId()]);
    }

    /**
     * @When /^I specify (?:their|his) code as "([^"]*)"$/
     * @When I do not specify its code
     */
    public function iSpecifyItsCodeAs($code = null)
    {
        $this->createPage->specifyCode($code);
    }

    /**
     * @When /^I specify (?:their|his) name as "([^"]*)"$/
     * @When I do not specify its name
     */
    public function iSpecifyItsNameAs($name = null)
    {
        $this->createPage->nameIt($name);
    }

    /**
     * @When I attach the :path image
     */
    public function iAttachImage($path)
    {
        $currentPage = $this->resolveCurrentPage();

        $currentPage->attachImage($path);
    }

    /**
     * @When I change its name as :name
     */
    public function iChangeItsNameAs($name)
    {
        $this->updatePage->nameIt($name);
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
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @When I delete dealer with name :fullName
     */
    public function iDeleteDealerWithName($fullName)
    {
        $this->indexPage->deleteResourceOnPage(['name' => $fullName]);
    }

    /**
     * @Then I should be notified that the name is required
     */
    public function iShouldBeNotifiedThatNameIsRequired()
    {
        Assert::same($this->createPage->getValidationMessage('name'), 'This value should not be blank.');
    }

    /**
     * @Then I should be notified that the file is not a valid image
     */
    public function iShouldBeNotifiedThatFileIsNotAValidImage()
    {
        Assert::same($this->createPage->getValidationMessage('image_file'), 'This file is not a valid image.');
    }

    /**
     * @Then /^there should be (\d+) dealers in the list$/
     */
    public function iShouldSeeDealersInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
    }

    /**
     * @Then this dealer should not be added
     */
    public function thisDealerShouldNotBeAdded()
    {
        $this->indexPage->open();

        Assert::same($this->indexPage->countItems(), 0);
    }

    /**
     * @Then the dealer :dealer should appear in the website
     * @Then I should see the dealer :dealer in the list
     */
    public function theDealerShould(Dealer $dealer)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $dealer->getName()]));
    }

    /**
     * @Then this dealer with name :fullName should appear in the website
     */
    public function thisDealerWithNameShouldAppearInTheStore($name)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }

    /**
     * @Then there should not be :name dealer anymore
     */
    public function thereShouldBeNoAnymore($name)
    {
        Assert::false($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }

    /**
     * @Then the dealer :dealerName should have an image
     */
    public function theDealerShouldHaveAnImage()
    {
        Assert::true($this->updatePage->hasOneImage());
    }

    /**
     * @return CreatePage|UpdatePage
     */
    private function resolveCurrentPage(): SymfonyPageInterface
    {
        return $this->currentPageResolver->getCurrentPageWithForm([
            $this->createPage,
            $this->updatePage,
        ]);
    }
}
