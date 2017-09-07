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

use AppBundle\Behat\Page\Frontend\Article\IndexPage;
use AppBundle\Behat\Page\Frontend\Article\ShowPage;
use AppBundle\Behat\Page\UnexpectedPageException;
use AppBundle\Entity\Article;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleContext implements Context
{
    /**
     * @var ShowPage
     */
    private $showPage;

    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @param ShowPage $showPage
     * @param IndexPage $indexPage
     */
    public function __construct(ShowPage $showPage, IndexPage $indexPage)
    {
        $this->showPage = $showPage;
        $this->indexPage = $indexPage;
    }

    /**
     * @When I want to browse articles
     */
    public function iWantToBrowseArticles()
    {
        $this->indexPage->open();
    }

    /**
     * @When /^I check (this article)'s details$/
     */
    public function iOpenArticlePage(Article $article)
    {
        $this->showPage->open(['slug' => $article->getSlug()]);
    }

    /**
     * @Then I should see the article :title
     */
    public function iShouldSeeArticle($title)
    {
        Assert::true($this->indexPage->isArticleOnList($title));
    }

    /**
     * @Then I should not see the article :title
     */
    public function iShouldNotSeeArticle($title)
    {
        Assert::false($this->indexPage->isArticleOnList($title));
    }

    /**
     * @Then I should see the article title :title
     */
    public function iShouldSeePersonName($title)
    {
        Assert::same($this->showPage->getTitle(), $title);
    }

    /**
     * @Then /^I should be able to see (this article)'s details$/
     */
    public function iShouldBeAbleToSeeArticleDetails(Article $article)
    {
        try {
            $this->iOpenArticlePage($article);

        } catch (UnexpectedPageException $exception) {
            // nothing else to do
        }

        Assert::true($this->showPage->isOpen(['slug' => $article->getSlug()]));
    }

    /**
     * @Then /^I should not be able to see (this article)'s details$/
     */
    public function iShouldNotBeAbleToSeeArticleDetails(Article $article)
    {
        try {
            $this->iOpenArticlePage($article);

        } catch (UnexpectedPageException $exception) {
            // nothing else to do
        }

        Assert::false($this->showPage->isOpen(['slug' => $article->getSlug()]));
    }
}
