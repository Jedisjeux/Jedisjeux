<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Context\Ui\Frontend;

use App\Tests\Behat\Page\Frontend\Account\StatisticsPage;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

final class AccountStatisticsContext implements Context
{
    public function __construct(private readonly StatisticsPage $statisticsPage)
    {
    }

    /**
     * @When I check my statistics of :year
     */
    public function iCheckMyStatisticsOf(string $year): void
    {
        $this->statisticsPage->open(['annee' => $year]);
    }

    /**
     * @Then /^I should see that I played (\d+) game plays?$/
     */
    public function iShouldSeeThatIPlayedGamePlays(string $count): void
    {
        Assert::same($this->statisticsPage->getGamePlayCount(), (int) $count);
    }

    /**
     * @Then /^I should see that I played (\d+) different games?$/
     */
    public function iShouldSeeThatIPlayedDifferentGames(string $count): void
    {
        Assert::same($this->statisticsPage->getProductCount(), (int) $count);
    }

    /**
     * @Then my most played games should be :firstGame with :firstCount and :secondGame with :secondCount
     */
    public function myMostPlayedGamesShouldBe(string $firstGame, string $firstCount, string $secondGame, string $secondCount): void
    {
        Assert::same($this->statisticsPage->getMostPlayedGames(), [
            $firstGame => (int) $firstCount,
            $secondGame => (int) $secondCount,
        ]);
    }

    /**
     * @Then my most played game should be :game with :count
     */
    public function myMostPlayedGameShouldBe(string $game, string $count): void
    {
        Assert::same($this->statisticsPage->getMostPlayedGames(), [$game => (int) $count]);
    }

    /**
     * @Then I should be notified that I have no game play
     */
    public function iShouldBeNotifiedThatIHaveNoGamePlay(): void
    {
        Assert::true($this->statisticsPage->hasNoGamePlay());
    }
}
