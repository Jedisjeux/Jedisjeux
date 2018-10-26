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

use App\Behat\Page\Frontend\Article\IndexByProductPage;
use App\Behat\Page\Frontend\Article\IndexByTaxonPage;
use App\Behat\Page\Frontend\Article\IndexPage;
use App\Behat\Page\Frontend\Article\ShowPage;
use App\Behat\Page\UnexpectedPageException;
use App\Behat\Service\Resolver\CurrentPageResolverInterface;
use App\Entity\Article;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;
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
     * @var IndexByProductPage
     */
    private $indexByProductPage;

    /**
     * @var CurrentPageResolverInterface
     */
    private $currentPageResolver;

    /**
     * @param ShowPage                     $showPage
     * @param IndexPage                    $indexPage
     * @param IndexByTaxonPage             $indexByTaxonPage
     * @param IndexByProductPage           $indexByProductPage
     * @param CurrentPageResolverInterface $currentPageResolver
     */
    public function __construct(
        ShowPage $showPage,
        IndexPage $indexPage,
        IndexByTaxonPage $indexByTaxonPage,
        IndexByProductPage $indexByProductPage,
        CurrentPageResolverInterface $currentPageResolver
    ) {
        $this->showPage = $showPage;
        $this->indexPage = $indexPage;
        $this->indexByTaxonPage = $indexByTaxonPage;
        $this->indexByProductPage = $indexByProductPage;
        $this->currentPageResolver = $currentPageResolver;
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
     * @When /^I check (this product)'s articles$/
     */
    public function iCheckThisProductArticles(ProductInterface $product)
    {
        $this->indexByProductPage->open(['productSlug' => $product->getSlug()]);
    }

    /**
     * @When /^I view (oldest|newest) articles$/
     */
    public function iViewSortedArticles($sortDirection)
    {
        $sorting = ['publishStartDate' => 'oldest' === $sortDirection ? 'asc' : 'desc'];

        $this->indexPage->open(['sorting' => $sorting]);
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
        /** @var IndexPage|ShowPage|IndexByTaxonPage $currentPage */
        $currentPage = $this->currentPageResolver->getCurrentPageWithForm([$this->indexPage, $this->showPage, $this->indexByTaxonPage]);

        Assert::true($currentPage->isArticleOnList($title));
    }

    /**
     * @Then I should not see the article :title
     */
    public function iShouldNotSeeArticle($title)
    {
        Assert::false($this->indexPage->isArticleOnList($title));
    }

    /**
     * @Then I should see :numberOfArticles articles in the list
     */
    public function iShouldSeeArticlesInTheList($numberOfArticles)
    {
        Assert::same($this->indexPage->countArticlesItems(), (int) $numberOfArticles);
    }

    /**
     * @Then the first article on the list should have title :title
     */
    public function theFirstArticleOnTheListShouldHaveTitle($title)
    {
        Assert::same($this->indexPage->getFirstArticleTitleFromList(), $title);
    }

    /**
     * @Then I should see the article title :title
     */
    public function iShouldSeeArticleTitle($title)
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

    /**
     * @Then /^I should be notified that there are no articles$/
     */
    public function iShouldBeNotifiedThatThereAreNoReviews()
    {
        Assert::true($this->indexPage->hasNoArticlesMessage());
    }
}
