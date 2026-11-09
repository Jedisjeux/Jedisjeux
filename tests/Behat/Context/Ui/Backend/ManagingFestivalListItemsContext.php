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

namespace App\Tests\Behat\Context\Ui\Backend;

use App\Entity\FestivalList;
use App\Entity\Product;
use App\Tests\Behat\Page\Backend\FestivalListItem\CreatePage;
use App\Tests\Behat\Page\Backend\FestivalListItem\IndexPage;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

final class ManagingFestivalListItemsContext implements Context
{
    private ?FestivalList $festivalList = null;

    public function __construct(
        private readonly IndexPage $indexPage,
        private readonly CreatePage $createPage,
    ) {
    }

    /**
     * @When I want to browse the games of festival list :festivalList
     */
    public function iWantToBrowseTheGamesOfFestivalList(FestivalList $festivalList): void
    {
        $this->festivalList = $festivalList;
        $this->indexPage->open(['festivalListId' => $festivalList->getId()]);
    }

    /**
     * @Given I want to add a game to festival list :festivalList
     */
    public function iWantToAddAGameToFestivalList(FestivalList $festivalList): void
    {
        $this->festivalList = $festivalList;
        $this->createPage->open(['festivalListId' => $festivalList->getId()]);
    }

    /**
     * @When I choose :product product
     */
    public function iChooseProduct(Product $product): void
    {
        $this->createPage->chooseProduct((string) $product->getCode());
    }

    /**
     * @When I add this game
     * @When I try to add this game
     */
    public function iAddIt(): void
    {
        $this->createPage->create();
    }

    /**
     * @When I remove the game :name from the festival list
     */
    public function iRemoveTheGameFromTheFestivalList(string $name): void
    {
        $this->indexPage->deleteResourceOnPage(['product' => $name]);
    }

    /**
     * @Then /^there should be (\d+) games? in the festival list$/
     */
    public function thereShouldBeGamesInTheFestivalList(int $number): void
    {
        $this->openIndexPage();

        Assert::same($this->indexPage->countItems(), $number);
    }

    /**
     * @Then I should see the game :name in the festival list
     */
    public function iShouldSeeTheGameInTheFestivalList(string $name): void
    {
        $this->openIndexPage();

        Assert::true($this->indexPage->isSingleResourceOnPage(['product' => $name]));
    }

    /**
     * @Then I should not see the game :name in the festival list
     */
    public function iShouldNotSeeTheGameInTheFestivalList(string $name): void
    {
        $this->openIndexPage();

        Assert::false($this->indexPage->isSingleResourceOnPage(['product' => $name]));
    }

    /**
     * @Then I should be notified that this game is already in the festival list
     */
    public function iShouldBeNotifiedThatThisGameIsAlreadyInTheFestivalList(): void
    {
        Assert::true($this->createPage->hasErrorMessage('This game is already in this list.'));
    }

    private function openIndexPage(): void
    {
        Assert::notNull($this->festivalList);

        $this->indexPage->open(['festivalListId' => $this->festivalList->getId()]);
    }
}
