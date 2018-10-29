<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Ui\Backend;

use App\Behat\Page\Backend\Person\IndexPage;
use App\Behat\Page\Backend\Person\UpdatePage;
use App\Behat\Page\Backend\Person\CreatePage;
use App\Behat\Service\Resolver\CurrentPageResolverInterface;
use App\Entity\Person;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ManagingPeopleContext implements Context
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
     * @Given I want to create a new person
     */
    public function iWantToCreateANewPerson()
    {
        $this->createPage->open();
    }

    /**
     * @When I want to browse people
     */
    public function iWantToBrowsePeople()
    {
        $this->indexPage->open();
    }

    /**
     * @Given I want to edit :person person
     */
    public function iWantToEditThePerson(Person $person)
    {
        $this->updatePage->open(['id' => $person->getId()]);
    }

    /**
     * @When /^I specify (?:their|his) first name as "([^"]*)"$/
     * @When I do not specify its first name
     */
    public function iSpecifyItsFirstNameAs($firstName = null)
    {
        $this->createPage->specifyFirstName($firstName);
    }

    /**
     * @When /^I specify (?:their|his) last name as "([^"]*)"$/
     * @When I do not specify its last name
     */
    public function iSpecifyItsLastNameAs($lastName = null)
    {
        $this->createPage->specifyLastName($lastName);
    }

    /**
     * @When I change its first name as :firstName
     */
    public function iChangeItsFirstNameAs($firstName)
    {
        $this->updatePage->changeFirstName($firstName);
    }

    /**
     * @When I change its last name as :lastName
     */
    public function iChangeItsLastNameAs($lastName)
    {
        $this->updatePage->changeLastName($lastName);
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
     * @When I delete person with name :fullName
     */
    public function iDeletePersonWithName($fullName)
    {
        $this->indexPage->deleteResourceOnPage(['slug' => $fullName]);
    }

    /**
     * @Then I should be notified that the last name is required
     */
    public function iShouldBeNotifiedThatLastNameIsRequired()
    {
        Assert::same($this->createPage->getValidationMessage('last_name'), 'This value should not be blank.');
    }

    /**
     * @Then /^there should be (\d+) people in the list$/
     */
    public function iShouldSeePeopleInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
    }

    /**
     * @Then this person should not be added
     */
    public function thisPersonShouldNotBeAdded()
    {
        $this->indexPage->open();

        Assert::same($this->indexPage->countItems(), 0);
    }

    /**
     * @Then the person :person should appear in the website
     * @Then I should see the person :person in the list
     */
    public function thePersonShould(Person $person)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['slug' => $person->getFullName()]));
    }

    /**
     * @Then this person with name :fullName should appear in the website
     */
    public function thisPersonWithNameShouldAppearInTheStore($fullName)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['slug' => $fullName]));
    }

    /**
     * @Then there should not be :fullName person anymore
     */
    public function thereShouldBeNoAnymore($fullName)
    {
        Assert::false($this->indexPage->isSingleResourceOnPage(['slug' => $fullName]));
    }
}
