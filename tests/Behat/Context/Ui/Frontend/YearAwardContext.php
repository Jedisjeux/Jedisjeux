<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Ui\Frontend;

use App\Tests\Behat\Page\Frontend\YearAward\IndexPage;
use App\Entity\GameAward;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

final class YearAwardContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
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
     * @When I view year awards from :gameAward
     */
    public function iViewFilteredProductsWithDurationUpToMinutes(GameAward $gameAward)
    {
        $criteria = ['award' => $gameAward->getId()];

        $this->indexPage->open(['criteria' => $criteria]);
    }

    /**
     * @Then I should see the year award :name
     */
    public function iShouldSeeYearAward($name)
    {
        Assert::true($this->indexPage->isYearAwardOnList($name));
    }

    /**
     * @Then I should not see the year award :name
     */
    public function iShouldNotSeeYearAward($name)
    {
        Assert::false($this->indexPage->isYearAwardOnList($name));
    }
}
