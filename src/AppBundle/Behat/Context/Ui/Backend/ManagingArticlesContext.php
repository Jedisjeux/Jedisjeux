<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Ui\Backend;

use AppBundle\Behat\Page\Backend\Article\IndexPage;
use AppBundle\Behat\Page\Backend\Article\UpdatePage;
use AppBundle\Behat\Page\Backend\Article\CreatePage;
use AppBundle\Behat\Page\UnexpectedPageException;
use AppBundle\Behat\Service\Resolver\CurrentPageResolverInterface;
use AppBundle\Entity\Article;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ManagingArticlesContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @var CreatePage
     */
    private $createPage;

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
     * @param IndexPage $indexPage
     * @param CreatePage $createPage
     * @param UpdatePage $updatePage
     * @param CurrentPageResolverInterface $currentPageResolver
     */
    public function __construct(
        IndexPage $indexPage,
        CreatePage $createPage,
        UpdatePage $updatePage,
        CurrentPageResolverInterface $currentPageResolver)
    {
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
        $this->currentPageResolver = $currentPageResolver;
    }

    /**
     * @Given I want to create a new article
     */
    public function iWantToCreateANewArticle()
    {
        $this->createPage->open();
    }

    /**
     * @When I want to browse articles
     */
    public function iWantToBrowseArticles()
    {
        $this->indexPage->open();
    }

    /**
     * @Given I want to edit :article article
     */
    public function iWantToEditTheArticle(Article $article)
    {
        $this->updatePage->open(['id' => $article->getId()]);
    }

    /**
     * @When /^I specify (?:their|his) title as "([^"]*)"$/
     * @When I do not specify its title
     */
    public function iSpecifyItsTitleAs($title = null)
    {
        $this->createPage->specifyTitle($title);
    }

    /**
     * @When I change its title as :title
     */
    public function iChangeItsTitleAs($title)
    {
        $this->updatePage->changeTitle($title);
    }

    /**
     * @When I add it
     * @When I try to add it
     */
    public function iAddIt()
    {
        $this->createPage->create();
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @When I delete article with title :title
     */
    public function iDeleteArticleWithTitle($title)
    {
        $this->indexPage->deleteResourceOnPage(['title' => $title]);
    }

    /**
     * @Then I should be notified that the title is required
     */
    public function iShouldBeNotifiedThatTitleIsRequired()
    {
        Assert::same($this->createPage->getValidationMessage('title'), 'This value should not be blank.');
    }

    /**
     * @Then /^there should be (\d+) articles in the list$/
     */
    public function iShouldSeeArticlesInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int)$number);
    }

    /**
     * @Then this article should not be added
     */
    public function thisArticleShouldNotBeAdded()
    {
        $this->indexPage->open();

        Assert::same($this->indexPage->countItems(), 0);
    }

    /**
     * @Then the article :article should appear in the website
     * @Then I should see the article :article in the list
     */
    public function theArticleShould(Article $article)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['title' => $article->getTitle()]));
    }

    /**
     * @Then this article with title :title should appear in the website
     */
    public function thisArticleWithTitleShouldAppearInTheStore($title)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['title' => $title]));
    }

    /**
     * @Then there should not be :title article anymore
     */
    public function thereShouldBeNoAnymore($title)
    {
        Assert::false($this->indexPage->isSingleResourceOnPage(['title' => $title]));
    }

    /**
     * @Then I should not be able to browse articles
     */
    public function iShouldNotBeAbleToBrowseArticles()
    {
        try {
            $this->indexPage->open();

        } catch (UnexpectedPageException $exception) {
            // nothing else to do
        }

        Assert::false($this->indexPage->isOpen());
    }
}
