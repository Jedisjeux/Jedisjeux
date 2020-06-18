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

use App\Tests\Behat\Page\Backend\DashboardPage;
use FriendsOfBehat\PageObjectExtension\Page\UnexpectedPageException;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DashboardContext implements Context
{
    /**
     * @var DashboardPage
     */
    private $dashboardPage;

    /**
     * DashboardContext constructor.
     *
     * @param DashboardPage $dashboardPage
     */
    public function __construct(DashboardPage $dashboardPage)
    {
        $this->dashboardPage = $dashboardPage;
    }

    /**
     * @When I open administration dashboard
     */
    public function iOpenAdministrationDashboard()
    {
        $this->dashboardPage->open();
    }

    /**
     * @Then I should be able to see the dashboard
     */
    public function iShouldBeAbleToSeeDashboard()
    {
        try {
            $this->dashboardPage->open();
        } catch (UnexpectedPageException $exception) {
            // nothing else to do
        }

        Assert::true($this->dashboardPage->isOpen());
    }
}
