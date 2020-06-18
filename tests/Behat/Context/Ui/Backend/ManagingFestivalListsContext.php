<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Ui\Backend;

use App\Tests\Behat\Page\Backend\FestivalList\CreatePage;
use App\Tests\Behat\Page\Backend\FestivalList\IndexPage;
use App\Entity\FestivalList;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ManagingFestivalListsContext implements Context
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
     * @param IndexPage  $indexPage
     * @param CreatePage $createPage
     */
    public function __construct(IndexPage $indexPage, CreatePage $createPage)
    {
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
    }

    /**
     * @Given I want to create a new festival list
     */
    public function iWantToCreateANewFestivalList()
    {
        $this->createPage->open();
    }

    /**
     * @When /^I specify its name as "([^"]*)"$/
     * @When I do not specify its name
     */
    public function iSpecifyItsNameAs($name = null)
    {
        $this->createPage->nameIt($name);
    }

    /**
     * @When /^I specify its description as "([^"]*)"$/
     * @When I do not specify its description
     */
    public function iSpecifyItsDescriptionAs($description = null)
    {
        $this->createPage->describeItAs($description);
    }

    /**
     * @When /^I specify its start at as "([^"]*)"$/
     * @When I do not specify its start at
     */
    public function iSpecifyItsStartAtAs($startAt = null)
    {
        $this->createPage->specifyStartAt($startAt);
    }

    /**
     * @When /^I specify its end at as "([^"]*)"$/
     * @When I do not specify its end at
     */
    public function iSpecifyItsEndAtAs($endAt = null)
    {
        $this->createPage->specifyEndAt($endAt);
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
     * @Then the festival list :festivalList should appear in the website
     * @Then I should see the festival list :festivalList in the list
     */
    public function theFestivalListShould(FestivalList $festivalList)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $festivalList->getName()]));
    }
}
