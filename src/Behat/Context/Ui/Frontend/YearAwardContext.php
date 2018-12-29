<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Ui\Frontend;

use App\Behat\Page\Frontend\YearAward\IndexPage;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

class YearAwardContext implements Context
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
     * @Then I should see the year award :name
     */
    public function iShouldSeeYearAward($name)
    {
        Assert::true($this->indexPage->isYearAwardOnList($name));
    }
}
