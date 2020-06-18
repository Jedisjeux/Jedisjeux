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

use App\Tests\Behat\Page\Backend\Redirection\IndexPage;
use App\Tests\Behat\Page\Backend\Redirection\UpdatePage;
use App\Tests\Behat\Page\Backend\Redirection\CreatePage;
use App\Tests\Behat\Service\Resolver\CurrentPageResolverInterface;
use App\Entity\Redirection;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ManagingRedirectionsContext implements Context
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

    /**
     * ManagingPeopleContext constructor.
     *
     * @param IndexPage                    $indexPage
     * @param CreatePage                   $createPage
     * @param UpdatePage                   $updatePage
     * @param CurrentPageResolverInterface $currentPageResolver
     */
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
     * @Given I want to create a new redirection
     */
    public function iWantToCreateANewRedirection()
    {
        $this->createPage->open();
    }

    /**
     * @When I want to browse redirections
     */
    public function iWantToBrowseRedirections()
    {
        $this->indexPage->open();
    }

    /**
     * @Given I want to edit :redirection redirection
     */
    public function iWantToEditTheRedirection(Redirection $redirection)
    {
        $this->updatePage->open(['id' => $redirection->getId()]);
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
     * @When /^I specify (?:their|his) source as "([^"]*)"$/
     * @When I do not specify its source
     */
    public function iSpecifyItsSourceAs($source = null)
    {
        $this->createPage->specifySource($source);
    }

    /**
     * @When /^I specify (?:their|his) destination as "([^"]*)"$/
     * @When I do not specify its destination
     */
    public function iSpecifyItsDestinationAs($destination = null)
    {
        $this->createPage->specifyDestination($destination);
    }

    /**
     * @When I change its source as :source
     */
    public function iChangeItsSourceAs($source)
    {
        $this->updatePage->changeSource($source);
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
     * @When I delete redirection with source :source
     */
    public function iDeleteRedirectionWithSource($source)
    {
        $this->indexPage->deleteResourceOnPage(['source' => $source]);
    }

    /**
     * @Then I should be notified that the source is required
     */
    public function iShouldBeNotifiedThatSourceIsRequired()
    {
        Assert::same($this->createPage->getValidationMessage('source'), 'Please enter a source.');
    }

    /**
     * @Then /^there should be (\d+) redirections in the list$/
     */
    public function iShouldSeeRedirectionsInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
    }

    /**
     * @Then this redirection should not be added
     */
    public function thisRedirectionShouldNotBeAdded()
    {
        $this->indexPage->open();

        Assert::same($this->indexPage->countItems(), 0);
    }

    /**
     * @Then the redirection :redirection should appear in the website
     * @Then I should see the redirection :redirection in the list
     */
    public function theRedirectionShould(Redirection $redirection)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['source' => $redirection->getSource()]));
    }

    /**
     * @Then this redirection with source :source should appear in the website
     */
    public function thisRedirectionWithSourceShouldAppearInTheStore($source)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['source' => $source]));
    }

    /**
     * @Then there should not be :source redirection anymore
     */
    public function thereShouldBeNoAnymore($source)
    {
        Assert::false($this->indexPage->isSingleResourceOnPage(['source' => $source]));
    }
}
