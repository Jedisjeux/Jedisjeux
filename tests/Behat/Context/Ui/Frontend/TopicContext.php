<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Ui\Frontend;

use App\Tests\Behat\Page\Frontend\Topic\CreateForTaxonPage;
use App\Tests\Behat\Page\Frontend\Topic\CreatePage;
use App\Tests\Behat\Page\Frontend\Topic\IndexByTaxonPage;
use App\Tests\Behat\Page\Frontend\Topic\IndexPage;
use App\Tests\Behat\Page\Frontend\Topic\ShowPage;
use App\Tests\Behat\Page\Frontend\Topic\UpdatePage;
use App\Entity\Topic;
use Behat\Behat\Context\Context;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @var IndexByTaxonPage
     */
    private $indexByTaxonPage;

    /**
     * @var CreatePage
     */
    private $createPage;

    /**
     * @var CreateForTaxonPage
     */
    private $createForTaxonPage;

    /**
     * @var UpdatePage
     */
    private $updatePage;

    /**
     * @var ShowPage
     */
    private $showPage;

    /**
     */
    public function __construct(
        IndexPage $indexPage,
        IndexByTaxonPage $indexByTaxonPage,
        CreatePage $createPage,
        CreateForTaxonPage $createForTaxonPage,
        UpdatePage $updatePage,
        ShowPage $showPage
    ) {
        $this->indexPage = $indexPage;
        $this->indexByTaxonPage = $indexByTaxonPage;
        $this->createPage = $createPage;
        $this->createForTaxonPage = $createForTaxonPage;
        $this->updatePage = $updatePage;
        $this->showPage = $showPage;
    }

    /**
     * @When I want to browse topics
     */
    public function iWantToBrowseTopics()
    {
        $this->indexPage->open();
    }

    /**
     * @When /^I want to browse topics from (taxon "([^"]+)")$/
     */
    public function iWantToBrowseTopicsFromTaxon(TaxonInterface $taxon)
    {
        $this->indexByTaxonPage->open(['slug' => $taxon->getSlug()]);
    }

    /**
     * @When /^I view (oldest|newest) topics$/
     */
    public function iViewSortedTopics($sortDirection)
    {
        $sorting = ['createdAt' => 'oldest' === $sortDirection ? 'asc' : 'desc'];

        $this->indexPage->open(['sorting' => $sorting]);
    }

    /**
     * @Given I want to add topic
     */
    public function iWantToAddTopic()
    {
        $this->createPage->open();
    }

    /**
     * @Given /^I want to add topic on ("([^"]+)" category)$/
     */
    public function iWantToAddTopicOnCategory(TaxonInterface $taxon)
    {
        $this->createForTaxonPage->open(['taxonId' => $taxon->getId()]);
    }

    /**
     * @Given /^I want to change (this topic)$/
     */
    public function iWantToChangeTopic(Topic $topic)
    {
        $this->updatePage->open(['id' => $topic->getId()]);
    }

    /**
     * @When /^I check (this topic)'s details$/
     */
    public function iOpenTopicPage(Topic $topic)
    {
        $this->showPage->open(['topicId' => $topic->getId()]);
    }

    /**
     * @When I specify its title as :title
     */
    public function iSpecifyTitle($title = null)
    {
        $this->createPage->setTitle($title);
    }

    /**
     * @When I leave a comment :comment, titled :title
     */
    public function iLeaveACommentTitled($comment = null, $title = null)
    {
        $this->createPage->setTitle($title);
        $this->createPage->setComment($comment);
    }

    /**
     * @When I change my comment as :comment
     * @When I do not leave any comment
     */
    public function iChangeMyCommentAs($comment = null)
    {
        $this->updatePage->setComment($comment);
    }

    /**
     * @When I add it
     * @When I try to add it
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
     * @Then I should see the topic :title
     */
    public function iShouldSeeTopic($title)
    {
        Assert::true($this->indexPage->isTopicOnList($title));
    }

    /**
     * @Then I should not see the topic :title
     */
    public function iShouldNotSeeTopic($title)
    {
        Assert::false($this->indexPage->isTopicOnList($title));
    }

    /**
     * @Then I should see the topic last post :comment
     */
    public function iShouldSeeTopicLastPost($comment)
    {
        Assert::true($this->indexPage->isLastPostComment($comment));
    }

    /**
     * @Then I should not see the topic last post :comment
     */
    public function iShouldNotSeeTopicLastPost($comment)
    {
        Assert::false($this->indexPage->isLastPostComment($comment));
    }

    /**
     * @Then I should see the topic title :title
     */
    public function iShouldSeeTopicTitle($title)
    {
        Assert::same($this->showPage->getTitle(), $title);
    }

    /**
     * @Then I should be notified that the :elementName is required
     */
    public function iShouldBeNotifiedThatCommentIsRequired($elementName)
    {
        Assert::true($this->createPage->checkValidationMessageFor($elementName, 'This value should not be blank.'));
    }

    /**
     * @Then this topic should not be added
     */
    public function thisTopicShouldNotBeAdded()
    {
        $this->indexPage->open();

        Assert::same($this->indexPage->countItems(), 0);
    }

    /**
     * @Then I should see :numberOfTopics topics in the list
     */
    public function iShouldSeeProductsInTheList($numberOfTopics)
    {
        Assert::same($this->indexPage->countItems(), (int) $numberOfTopics);
    }

    /**
     * @Then the first topic on the list should be :title
     */
    public function theFirstTopicOnTheListShouldBe($title)
    {
        Assert::same($this->indexPage->getFirstTopicTitleFromList(), $title);
    }
}
