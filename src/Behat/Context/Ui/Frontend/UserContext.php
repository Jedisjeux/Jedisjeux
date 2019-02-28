<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Ui\Frontend;

use App\Behat\Page\Frontend\User\GameLibraryPage;
use App\Entity\Customer;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

class UserContext implements Context
{
    /**
     * @var GameLibraryPage
     */
    private $gameLibraryPage;

    /**
     * @param GameLibraryPage $gameLibraryPage
     */
    public function __construct(GameLibraryPage $gameLibraryPage)
    {
        $this->gameLibraryPage = $gameLibraryPage;
    }

    /**
     * @When /^I check (his) game library's details$/
     */
    public function iCheckHisGameLibrary(Customer $customer)
    {
        $this->gameLibraryPage->open(['username' => $customer->getUser()->getUsernameCanonical()]);
    }

    /**
     * @Then /^there should be (\d+) games in the game library$/
     */
    public function iShouldSeeGamesInTheGameLibrary(int $numberOfGames)
    {
        Assert::same($this->gameLibraryPage->countGames(), (int) $numberOfGames);
    }
}
