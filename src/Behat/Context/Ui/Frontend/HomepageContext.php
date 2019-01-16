<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Ui\Frontend;

use App\Behat\Page\Frontend\HomePage;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

class HomepageContext implements Context
{
    /**
     * @var HomePage
     */
    private $homePage;

    /**
     * @param HomePage $homePage
     */
    public function __construct(HomePage $homePage)
    {
        $this->homePage = $homePage;
    }

    /**
     * @When I check latest articles
     * @When I check most popular articles
     * @When I check latest arrivals
     * @When I check counters
     */
    public function iCheckItems()
    {
        $this->homePage->open();
    }

    /**
     * @Then I should see :numberOfArticles articles in the latest articles list
     */
    public function iShouldSeeArticlesInTheLatestArticlesList($numberOfArticles)
    {
        Assert::same(count($this->homePage->getLatestArticlesTitles()), (int) $numberOfArticles);
    }

    /**
     * @Then I should see the article :title in the latest articles list
     */
    public function iShouldSeeTheArticleInTheLatestArticlesList($title)
    {
        Assert::true(in_array($title, $this->homePage->getLatestArticlesTitles()));
    }

    /**
     * @Then I should not see the article :title in the latest articles list
     */
    public function iShouldNotSeeTheArticleInTheLatestArticlesList($title)
    {
        Assert::false(in_array($title, $this->homePage->getLatestArticlesTitles()));
    }

    /**
     * @Then I should see :numberOfArticles articles in the most popular articles list
     */
    public function iShouldSeeArticlesInTheMostPopularArticlesList($numberOfArticles)
    {
        Assert::same(count($this->homePage->getMostPopularArticlesTitles()), (int) $numberOfArticles);
    }

    /**
     * @Then I should see the article :title in the most popular articles list
     */
    public function iShouldSeeTheArticleInTheMostPopularArticlesList($title)
    {
        Assert::true(in_array($title, $this->homePage->getMostPopularArticlesTitles()));
    }

    /**
     * @Then I should not see the article :title in the most popular articles list
     */
    public function iShouldNotSeeTheArticleInTheMostPopularArticlesList($title)
    {
        Assert::false(in_array($title, $this->homePage->getMostPopularArticlesTitles()));
    }

    /**
     * @Then I should see :numberOfProducts products in the latest arrivals list
     */
    public function iShouldSeeProductsInTheLatestArrivalsList($numberOfProducts)
    {
        Assert::same(count($this->homePage->getLatestArrivalsNames()), (int) $numberOfProducts);
    }

    /**
     * @Then I should see the product :name in the latest arrivals list
     */
    public function iShouldSeeTheProductInTheLatestArrivalsList(string $name)
    {
        Assert::true(in_array($name, $this->homePage->getLatestArrivalsNames()));
    }

    /**
     * @Then I should not see the product :name in the latest arrivals list
     */
    public function iShouldNotSeeTheProductInTheLatestArrivalsList(string $name)
    {
        Assert::false(in_array($name, $this->homePage->getLatestArrivalsNames()));
    }

    /**
     * @Then I should see :ratingCountValue as rating count
     */
    public function iShouldSeeRatingCountValue($ratingCountValue)
    {
        Assert::same($this->homePage->getRatingCount(), (int) $ratingCountValue);
    }

    /**
     * @Then I should see :productCountValue as product count
     */
    public function iShouldSeeProductCountValue($productCountValue)
    {
        Assert::same($this->homePage->getProductCount(), (int) $productCountValue);
    }
}
