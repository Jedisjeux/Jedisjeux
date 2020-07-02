<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Ui\Frontend;

use App\Tests\Behat\Page\Frontend\GamePlay\CreatePage;
use App\Tests\Behat\Page\Frontend\GamePlay\IndexByProductPage;
use App\Tests\Behat\Page\Frontend\GamePlay\IndexPage;
use App\Tests\Behat\Page\Frontend\GamePlay\UpdatePage;
use App\Entity\GamePlay;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;
use Webmozart\Assert\Assert;

final class GamePlayContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @var IndexByProductPage
     */
    private $indexByProductPage;

    /**
     * @var CreatePage
     */
    private $createPage;

    /**
     * @var UpdatePage
     */
    private $updatePage;

    public function __construct(
        IndexPage $indexPage,
        IndexByProductPage $indexByProductPage,
        CreatePage $createPage,
        UpdatePage $updatePage
    ) {
        $this->indexPage = $indexPage;
        $this->indexByProductPage = $indexByProductPage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
    }

    /**
     * @Given /^I want to add game play of (this product)$/
     */
    public function iWantToAddGamePlay(ProductInterface $product)
    {
        $this->createPage->open(['productSlug' => $product->getSlug()]);
    }

    /**
     * @Given /^I want to edit (this game play)$/
     */
    public function iWantToEditGamePlay(GamePlay $gamePlay)
    {
        $this->updatePage->open([
            'id' => $gamePlay->getId(),
            'productSlug' => $gamePlay->getProduct()->getSlug(),
        ]);
    }

    /**
     * @When I want to browse game plays
     */
    public function iWantToBrowseGamePlays()
    {
        $this->indexPage->open();
    }

    /**
     * @When /^I check (this product)'s game plays$/
     */
    public function iCheckThisProductGamePlays(ProductInterface $product)
    {
        $this->indexByProductPage->open(['productSlug' => $product->getSlug()]);
    }

    /**
     * @When /^I specify its playing date as "([^"]*)"$/
     * @When I do not specify its playing date
     */
    public function iSpecifyItsPlayingDateAs(string $playedAt = null)
    {
        $this->createPage->setPlayedAt($playedAt);
    }

    /**
     * @When /^I specify its duration as "([^"]*)"$/
     * @When I do not specify its duration
     */
    public function iSpecifyItsDurationAs(int $duration = null)
    {
        $this->createPage->setDuration($duration);
    }

    /**
     * @When /^I specify its player count as "([^"]*)"$/
     * @When I do not specify its player count
     */
    public function iSpecifyItsPlayerCountAs(int $playerCount = null)
    {
        $this->createPage->setPlayerCount($playerCount);
    }

    /**
     * @When I change its duration as :playedAt
     */
    public function iChangeItsDurationAtAs($duration)
    {
        $this->updatePage->setDuration($duration);
    }

    /**
     * @When I change its player count as :playerCount
     */
    public function iChangeItsPlayerCountAs($playerCount)
    {
        $this->updatePage->setPlayerCount($playerCount);
    }

    /**
     * @When I add it
     */
    public function iAddIt()
    {
        $this->createPage->submit();
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->submit();
    }

    /**
     * @Then I should see the game play from product :productName
     */
    public function iShouldSeeGamePlayFromProduct($productName)
    {
        Assert::true($this->indexPage->isProductOnList($productName));
    }

    /**
     * @Then I should not see the game play from product :productName
     */
    public function iShouldNotSeeGamePlayFromProduct($productName)
    {
        Assert::false($this->indexPage->isProductOnList($productName));
    }

    /**
     * @Then I should see :numberOfGamePlays game plays in the list
     */
    public function iShouldSeeArticlesInTheList($numberOfGamePlays)
    {
        Assert::same($this->indexPage->countGamePlays(), (int) $numberOfGamePlays);
    }

    /**
     * @Then /^I should be notified that there are no game plays$/
     */
    public function iShouldBeNotifiedThatThereAreNoReviews()
    {
        Assert::true($this->indexPage->hasNoGamePlaysMessage());
    }
}
