<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Ui\Frontend;

use App\Tests\Behat\Page\Frontend\User\GameLibraryPage;
use App\Tests\Behat\Page\Frontend\User\ShowPage;
use App\Entity\Customer;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

class UserContext implements Context
{
    /**
     * @var ShowPage
     */
    private $showPage;

    /**
     * @var GameLibraryPage
     */
    private $gameLibraryPage;

    /**
     * @param ShowPage        $showPage
     * @param GameLibraryPage $gameLibraryPage
     */
    public function __construct(ShowPage $showPage, GameLibraryPage $gameLibraryPage)
    {
        $this->showPage = $showPage;
        $this->gameLibraryPage = $gameLibraryPage;
    }

    /**
     * @When /^I check (his) details$/
     */
    public function iCheckHisDetails(Customer $customer)
    {
        $this->showPage->open(['username' => $customer->getUser()->getUsernameCanonical()]);
    }

    /**
     * @When /^I check (his) game library's details$/
     */
    public function iCheckHisGameLibrary(Customer $customer)
    {
        $this->gameLibraryPage->open(['username' => $customer->getUser()->getUsernameCanonical()]);
    }

    /**
     * @Then I should see the username :name
     */
    public function iShouldSeeUsername($name)
    {
        Assert::same($this->showPage->getUsername(), $name);
    }

    /**
     * @Then /^there should be (\d+) games in the game library$/
     */
    public function iShouldSeeGamesInTheGameLibrary(int $numberOfGames)
    {
        Assert::same($this->gameLibraryPage->countGames(), (int) $numberOfGames);
    }
}
