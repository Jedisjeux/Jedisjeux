<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Ui\Frontend;

use AppBundle\Behat\Page\Frontend\Person\IndexPage;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PersonContext implements Context
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
     * @When I want to browse people
     */
    public function iWantToBrowsePeople()
    {
        $this->indexPage->open();
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
}
