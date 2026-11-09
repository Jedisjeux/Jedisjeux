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

use App\Entity\GameAward;
use App\Entity\ProductList;
use App\Tests\Behat\Page\Frontend\Account\GamePlayIndexPage;
use App\Tests\Behat\Page\Frontend\Account\GamesLibraryPage;
use App\Tests\Behat\Page\Frontend\Account\ProductListIndexPage;
use App\Tests\Behat\Page\Frontend\Account\ProductListItemIndexPage as AccountProductListItemIndexPage;
use App\Tests\Behat\Page\Frontend\GamePlayImage\IndexPage as GamePlayImageIndexPage;
use App\Tests\Behat\Page\Frontend\NamesListPageInterface;
use App\Tests\Behat\Page\Frontend\Person\IndexByZonePage;
use App\Tests\Behat\Page\Frontend\ProductList\ProductListItemIndexPage;
use App\Tests\Behat\Page\Frontend\YearAward\IndexByAwardPage;
use Behat\Behat\Context\Context;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Webmozart\Assert\Assert;

/**
 * Opens the front-end pages listing items by their name, and checks these names.
 */
final class BrowsingListsContext implements Context
{
    private ?NamesListPageInterface $currentPage = null;

    public function __construct(
        private readonly GamePlayIndexPage $myGamePlaysPage,
        private readonly GamesLibraryPage $myGamesLibraryPage,
        private readonly ProductListIndexPage $myProductListsPage,
        private readonly AccountProductListItemIndexPage $myProductListPage,
        private readonly ProductListItemIndexPage $productListPage,
        private readonly GamePlayImageIndexPage $gamePlayImagesPage,
        private readonly IndexByZonePage $peopleByZonePage,
        private readonly IndexByAwardPage $yearAwardsByAwardPage,
    ) {
    }

    /**
     * @When I browse my game plays
     */
    public function iBrowseMyGamePlays(): void
    {
        $this->open($this->myGamePlaysPage);
    }

    /**
     * @When I browse my game library
     */
    public function iBrowseMyGameLibrary(): void
    {
        $this->open($this->myGamesLibraryPage);
    }

    /**
     * @When I browse my game lists
     */
    public function iBrowseMyGameLists(): void
    {
        $this->open($this->myProductListsPage);
    }

    /**
     * @When /^I check my game list (this product list)$/
     */
    public function iCheckMyGameList(ProductList $productList): void
    {
        $this->open($this->myProductListPage, ['productListSlug' => $productList->getSlug()]);
    }

    /**
     * @When /^I check the game list (this product list)$/
     */
    public function iCheckTheGameList(ProductList $productList): void
    {
        $this->open($this->productListPage, ['productListSlug' => $productList->getSlug()]);
    }

    /**
     * @When I browse the game play images
     */
    public function iBrowseTheGamePlayImages(): void
    {
        $this->open($this->gamePlayImagesPage);
    }

    /**
     * @When /^I browse the people from ("[^"]+" zone)$/
     */
    public function iBrowseThePeopleFromZone(TaxonInterface $zone): void
    {
        $this->open($this->peopleByZonePage, ['slug' => $zone->getSlug()]);
    }

    /**
     * @When /^I check the page of the (game award "[^"]+")$/
     */
    public function iCheckThePageOfTheGameAward(GameAward $gameAward): void
    {
        $this->open($this->yearAwardsByAwardPage, ['slug' => $gameAward->getSlug()]);
    }

    /**
     * @Then I should see :name in this list
     */
    public function iShouldSeeInThisList(string $name): void
    {
        Assert::oneOf($name, $this->getNames());
    }

    /**
     * @Then I should not see :name in this list
     */
    public function iShouldNotSeeInThisList(string $name): void
    {
        Assert::false(in_array($name, $this->getNames(), true), sprintf('"%s" should not be in the list.', $name));
    }

    /**
     * @Then /^there should be (\d+) items? in this list$/
     */
    public function thereShouldBeItemsInThisList(int $count): void
    {
        Assert::count($this->getNames(), $count);
    }

    /**
     * @param array<string, mixed> $parameters
     */
    private function open(NamesListPageInterface $page, array $parameters = []): void
    {
        $page->open($parameters);
        $this->currentPage = $page;
    }

    /**
     * @return string[]
     */
    private function getNames(): array
    {
        Assert::notNull($this->currentPage, 'No list has been opened.');

        return $this->currentPage->getNames();
    }
}
