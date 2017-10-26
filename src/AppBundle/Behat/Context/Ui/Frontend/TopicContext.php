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
     * @param IndexPage $indexPage
     * @param IndexByTaxonPage $indexByTaxonPage
     * @param CreatePage $createPage
     */
    public function __construct(
        IndexPage $indexPage,
        IndexByTaxonPage $indexByTaxonPage,
        CreatePage $createPage
    ) {
        $this->indexPage = $indexPage;
        $this->indexByTaxonPage = $indexByTaxonPage;
        $this->createPage = $createPage;
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
     * @When I leave a comment :comment, titled :title
     */
    public function iLeaveACommentTitled($comment = null, $title = null)
    {
        $this->createPage->setTitle($title);
        $this->createPage->setComment($comment);
    }

    /**
     * @When I add it
     */
    public function iAddIt()
    {
        $this->createPage->submit();
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
