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

use AppBundle\Behat\Page\Frontend\Topic\CreatePage;
use AppBundle\Behat\Page\Frontend\Topic\IndexByTaxonPage;
use AppBundle\Behat\Page\Frontend\Topic\IndexPage;
use AppBundle\Behat\Page\Frontend\Topic\UpdatePage;
use AppBundle\Entity\Topic;
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
     * @var UpdatePage
     */
    private $updatePage;

    /**
     * @param IndexPage $indexPage
     * @param IndexByTaxonPage $indexByTaxonPage
     * @param CreatePage $createPage
     * @param UpdatePage $updatePage
     */
    public function __construct(
        IndexPage $indexPage,
        IndexByTaxonPage $indexByTaxonPage,
        CreatePage $createPage,
        UpdatePage $updatePage
    ) {
        $this->indexPage = $indexPage;
        $this->indexByTaxonPage = $indexByTaxonPage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
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
     * @Given I want to add topic
     */
    public function iWantToAddTopic()
    {
        $this->createPage->open();
    }

    /**
     * @Given /^I want to change (this topic)$/
     */
    public function iWantToChangeTopic(Topic $topic)
    {
        $this->updatePage->open(['id' => $topic->getId()]);
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
     */
    public function iChangeMyCommentAs($comment)
    {
        $this->updatePage->setComment($comment);
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
}
