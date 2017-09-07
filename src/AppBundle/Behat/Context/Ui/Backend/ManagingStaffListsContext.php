<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Ui\Backend;

use AppBundle\Behat\Page\Backend\StaffList\CreatePage;
use AppBundle\Behat\Page\Backend\StaffList\IndexPage;
use AppBundle\Entity\StaffList;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ManagingStaffListsContext implements Context
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
     * @param IndexPage $indexPage
     * @param CreatePage $createPage
     */
    public function __construct(IndexPage $indexPage, CreatePage $createPage)
    {
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
    }

    /**
     * @Given I want to create a new staff list
     */
    public function iWantToCreateANewStaffList()
    {
        $this->createPage->open();
    }

    /**
     * @When /^I specify its name as "([^"]*)"$/
     * @When I do not specify its name
     */
    public function iSpecifyItsNameAs($name = null)
    {
        $this->createPage->specifyName($name);
    }

    /**
     * @When /^I specify its description as "([^"]*)"$/
     * @When I do not specify its description
     */
    public function iSpecifyItsDescriptionAs($description = null)
    {
        $this->createPage->specifyDescription($description);
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
     * @Then the staff list :staffList should appear in the website
     * @Then I should see the staff list :staffList in the list
     */
    public function theStaffListShould(StaffList $staffList)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $staffList->getName()]));
    }
}
