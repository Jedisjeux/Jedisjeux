<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Ui\Frontend;

use App\Tests\Behat\Page\Frontend\Person\IndexPage;
use App\Tests\Behat\Page\Frontend\Person\ShowPage;
use App\Entity\Person;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PersonContext implements Context
{
    /**
     * @var ShowPage
     */
    private $showPage;

    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @param ShowPage  $showPage
     * @param IndexPage $indexPage
     */
    public function __construct(ShowPage $showPage, IndexPage $indexPage)
    {
        $this->showPage = $showPage;
        $this->indexPage = $indexPage;
    }

    /**
     * @When I want to browse people
     */
    public function iWantToBrowsePeople()
    {
        $this->indexPage->open();
    }

    /**
     * @When /^I check (this person)'s details$/
     */
    public function iOpenPersonPage(Person $person)
    {
        $this->showPage->open(['slug' => $person->getSlug()]);
    }

    /**
     * @Then I should see the person :fullName
     */
    public function iShouldSeePerson($fullName)
    {
        Assert::true($this->indexPage->isPersonOnList($fullName));
    }

    /**
     * @Then I should not see the person :fullName
     */
    public function iShouldNotSeePerson($fullName)
    {
        Assert::false($this->indexPage->isPersonOnList($fullName));
    }

    /**
     * @Then I should see the person name :name
     */
    public function iShouldSeePersonName($name)
    {
        Assert::same($this->showPage->getName(), $name);
    }
}
