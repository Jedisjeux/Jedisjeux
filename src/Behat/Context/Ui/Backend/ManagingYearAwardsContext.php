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

use App\Behat\Page\Backend\YearAward\IndexPage;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

class ManagingYearAwardsContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @param IndexPage $indexPage
     */
    public function __construct(IndexPage $indexPage)
    {
        $this->indexPage = $indexPage;
    }

    /**
     * @When I want to browse year awards
     */
    public function iWantToBrowseYearAwards()
    {
        $this->indexPage->open();
    }

    /**
     * @Then /^there should be (\d+) year awards in the list$/
     */
    public function iShouldSeeGameAwardsInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
    }

    /**
     * @Then I should see the year award :name in the list
     */
    public function theGameAwardShould($name)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage([
            'name' => (string) $name,
        ]));
    }
}
