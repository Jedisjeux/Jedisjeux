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

use App\Behat\Page\Backend\DealerPrice\IndexPage;
use App\Behat\Page\Backend\DealerPrice\UpdatePage;
use App\Behat\Service\Resolver\CurrentPageResolverInterface;
use App\Entity\Dealer;
use App\Entity\DealerPrice;
use App\Entity\Topic;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ManagingDealerPricesContext implements Context
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
     * @var CurrentPageResolverInterface
     */
    private $currentPageResolver;

    /**
     * ManagingPeopleContext constructor.
     *
     * @param IndexPage                    $indexPage
     * @param UpdatePage                   $updatePage
     * @param CurrentPageResolverInterface $currentPageResolver
     */
    public function __construct(
        IndexPage $indexPage,
        UpdatePage $updatePage,
        CurrentPageResolverInterface $currentPageResolver)
    {
        $this->indexPage = $indexPage;
        $this->updatePage = $updatePage;
        $this->currentPageResolver = $currentPageResolver;
    }

    /**
     * @When I want to browse dealer prices
     */
    public function iWantToBrowseDealerPrices()
    {
        $this->indexPage->open();
    }

    /**
     * @Given I want to edit dealer price with page :dealerPrice
     */
    public function iWantToEditDealerPrice(DealerPrice $dealerPrice)
    {
        $this->updatePage->open(['id' => $dealerPrice->getId()]);
    }

    /**
     * @When I change its name as :name
     */
    public function iChangeItsNameAs($name)
    {
        $this->updatePage->nameIt($name);
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @When I delete dealer price with page :url
     */
    public function iDeleteDealerPriceWithUrl($url)
    {
        $this->indexPage->deleteResourceOnPage(['url' => $url]);
    }

    /**
     * @Then /^there should be (\d+) dealer prices in the list$/
     */
    public function iShouldSeeDealerPricesInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
    }

    /**
     * @Then I should see the url :url in the list
     */
    public function theUrlShould($url)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['url' => $url]));
    }

    /**
     * @Then this dealer price with name :name should appear in the website
     */
    public function thisDealerPriceWithNameShouldAppearInTheWebsite($name)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }

    /**
     * @Then this topic body should be :body
     */
    public function thisDealerPriceBodyShouldBe($body)
    {
        $this->assertElementValue('body', $body);
    }

    /**
     * @Then there should not be dealer price with page :url anymore
     */
    public function thereShouldBeNoDealerPriceWithPageAnymore($url)
    {
        Assert::false($this->indexPage->isSingleResourceOnPage(['url' => $url]));
    }

    /**
     * @param string $element
     * @param string $value
     */
    private function assertElementValue($element, $value)
    {
        Assert::true(
            $this->updatePage->hasResourceValues([$element => $value]),
            sprintf('DealerPrice should have %s with %s value.', $element, $value)
        );
    }
}
