<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Ui\Frontend;

use AppBundle\Behat\Page\Frontend\HomePage;
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
     */
    public function iCheckLatestArticles()
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
}
