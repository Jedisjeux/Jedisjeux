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

use App\Tests\Behat\Page\Backend\GameAward\UpdatePage;
use App\Tests\Behat\Page\Backend\GameAward\CreatePage;
use App\Tests\Behat\Page\Backend\GameAward\IndexPage;
use App\Entity\GameAward;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

class ManagingGameAwardsContext implements Context
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
     * @param CreatePage $createPage
     * @param IndexPage  $indexPage
     * @param UpdatePage $updatePage
     */
    public function __construct(CreatePage $createPage, IndexPage $indexPage, UpdatePage $updatePage)
    {
        $this->createPage = $createPage;
        $this->indexPage = $indexPage;
        $this->updatePage = $updatePage;
    }

    /**
     * @Given I want to create a new game award
     */
    public function iWantToCreateANewGameAward()
    {
        $this->createPage->open();
    }

    /**
     * @When I want to browse game awards
     */
    public function iWantToBrowseGameAwards()
    {
        $this->indexPage->open();
    }

    /**
     * @Given I want to edit :gameAward game award
     */
    public function iWantToEditTheGameAward(GameAward $gameAward)
    {
        $this->updatePage->open(['id' => $gameAward->getId()]);
    }

    /**
     * @When I name it :name
     */
    public function iSpecifyItsNameAs($name = null)
    {
        $this->createPage->nameIt($name);
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
     * @Then /^there should be (\d+) game awards in the list$/
     */
    public function iShouldSeeGameAwardsInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
    }

    /**
     * @Then I should see the game award :name in the list
     */
    public function theGameAwardShould($name)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage([
            'name' => (string) $name,
        ]));
    }
}
