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
     * @param IndexPage $indexPage
     * @param IndexByTaxonPage $indexByTaxonPage
     */
    public function __construct(IndexPage $indexPage, IndexByTaxonPage $indexByTaxonPage)
    {
        $this->indexPage = $indexPage;
        $this->indexByTaxonPage = $indexByTaxonPage;
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
