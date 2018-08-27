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

use AppBundle\Behat\Page\Frontend\Article\IndexByTaxonPage;
use AppBundle\Behat\Page\Frontend\Article\IndexPage;
use AppBundle\Behat\Page\Frontend\Article\ShowPage;
use AppBundle\Behat\Page\UnexpectedPageException;
use AppBundle\Entity\Article;
use Behat\Behat\Context\Context;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
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
     * @var IndexByTaxonPage
     */
    private $indexByTaxonPage;

    /**
     * @param ShowPage $showPage
     * @param IndexPage $indexPage
     * @param IndexByTaxonPage $indexByTaxonPage
     */
    public function __construct(ShowPage $showPage, IndexPage $indexPage, IndexByTaxonPage $indexByTaxonPage)
    {
        $this->showPage = $showPage;
        $this->indexPage = $indexPage;
        $this->indexByTaxonPage = $indexByTaxonPage;
    }

    /**
     * @When I want to browse articles
     */
    public function iWantToBrowseArticles()
    {
        $this->indexPage->open();
    }

    /**
     * @When /^I browse articles from (category "([^"]+)")$/
     */
    public function iCheckListOfArticlesFromCategory(TaxonInterface $taxon)
    {
        $this->indexByTaxonPage->open(['slug' => $taxon->getSlug()]);
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
