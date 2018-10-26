<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Ui\Backend;

use App\Behat\Page\Backend\GamePlay\IndexPage;
use App\Behat\Page\Backend\GamePlay\UpdatePage;
use App\Behat\Service\Resolver\CurrentPageResolverInterface;
use App\Entity\Customer;
use Behat\Behat\Context\Context;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ManagingGamePlaysContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @var UpdatePage
     */
    private $updatePage;

    /**
     * @var RepositoryInterface
     */
    private $gamePlayRepository;

    /**
     * @var CurrentPageResolverInterface
     */
    private $currentPageResolver;

    /**
     * ManagingPeopleContext constructor.
     *
     * @param IndexPage                    $indexPage
     * @param UpdatePage                   $updatePage
     * @param RepositoryInterface          $gamePlayRepository
     * @param CurrentPageResolverInterface $currentPageResolver
     */
    public function __construct(
        IndexPage $indexPage,
        UpdatePage $updatePage,
        RepositoryInterface $gamePlayRepository,
        CurrentPageResolverInterface $currentPageResolver)
    {
        $this->indexPage = $indexPage;
        $this->updatePage = $updatePage;
        $this->gamePlayRepository = $gamePlayRepository;
        $this->currentPageResolver = $currentPageResolver;
    }

    /**
     * @When I want to browse game plays
     */
    public function iWantToBrowseGamePlays()
    {
        $this->indexPage->open();
    }

    /**
     * @Given I want to edit game play of :product played by :customer
     */
    public function iWantToEditTheGamePlay(ProductInterface $product, CustomerInterface $customer)
    {
        $gamePlay = $this->gamePlayRepository->findOneBy([
            'product' => $product,
            'author' => $customer,
        ]);

        Assert::notNull($gamePlay, sprintf('Game play of %s played by %s was not found', $product->getName(), $customer->getEmail()));

        $this->updatePage->open(['id' => $gamePlay->getId()]);
    }

    /**
     * @When I change its played at as :playedAt
     */
    public function iChangeItsPlayedAtAs($playedAt)
    {
        $this->updatePage->changePlayedAt($playedAt);
    }

    /**
     * @When I change its duration as :playedAt
     */
    public function iChangeItsDurationAtAs($duration)
    {
        $this->updatePage->changeDuration($duration);
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @When I delete game play of :product played by :customer
     */
    public function iDeleteGamePlayWithTitle(ProductInterface $product, Customer $customer)
    {
        $productName = $product->getName();

        $this->indexPage->deleteResourceOnPage([
            'name' => (string) $customer,
            'product' => $productName,
        ]);
    }

    /**
     * @Then /^there should be (\d+) game plays in the list$/
     */
    public function iShouldSeeGamePlaysInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
    }

    /**
     * @Then the game play of :product played by :customer should appear in the website
     * @Then I should see the game play of :product played by :customer in the list
     */
    public function theGamePlayShould(ProductInterface $product, Customer $customer)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage([
            'name' => (string) $customer,
            'product' => $product->getName(),
        ]));
    }

    /**
     * @Then this game play should be played at :playedAt
     */
    public function thisGamePlayShouldBePlayedAt($playedAt)
    {
        $this->assertElementValue('played_at', $playedAt);
    }

    /**
     * @Then this game play duration should be :duration
     */
    public function thisGamePlayDurationShouldBe($duration)
    {
        $this->assertElementValue('duration', $duration);
    }

    /**
     * @Then there should not be game play of :product played by :customer anymore
     */
    public function thereShouldBeNoGamePlayWitAnymore(ProductInterface $product, Customer $customer)
    {
        Assert::false($this->indexPage->isSingleResourceOnPage([
            'name' => (string) $customer,
            'product' => $product->getName(),
        ]));
    }

    /**
     * @param string $element
     * @param string $value
     */
    private function assertElementValue($element, $value)
    {
        Assert::true(
            $this->updatePage->hasResourceValues([$element => $value]),
            sprintf('Game play should have %s with %s value.', $element, $value)
        );
    }
}
