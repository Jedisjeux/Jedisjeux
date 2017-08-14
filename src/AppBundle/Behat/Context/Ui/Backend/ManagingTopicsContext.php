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

use AppBundle\Behat\Page\Backend\Crud\IndexPageInterface;
use AppBundle\Behat\Page\Backend\Crud\UpdatePageInterface;
use AppBundle\Behat\Page\Backend\Topic\IndexPage;
use AppBundle\Behat\Page\Backend\Topic\UpdatePage;
use AppBundle\Behat\Page\SymfonyPageInterface;
use AppBundle\Behat\Service\Resolver\CurrentPageResolverInterface;
use AppBundle\Entity\Topic;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ManagingTopicsContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

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
     * @param UpdatePage $updatePage
     * @param CurrentPageResolverInterface $currentPageResolver
     */
    public function __construct(
        IndexPage $indexPage,
        UpdatePage $updatePage,
        CurrentPageResolverInterface $currentPageResolver)
    {
        $this->indexPage = $indexPage;
        $this->updatePage = $updatePage;
        $this->currentPageResolver = $currentPageResolver;
    }

    /**
     * @When I want to browse topics
     */
    public function iWantToBrowseTopics()
    {
        $this->indexPage->open();
    }

    /**
     * @Given I want to edit :topic topic
     */
    public function iWantToEditTheTopic(Topic $topic)
    {
        $this->updatePage->open(['id' => $topic->getId()]);
    }

    /**
     * @When I change its title as :title
     */
    public function iChangeItsTitleAs($title)
    {
        $this->updatePage->changeTitle($title);
    }

    /**
     * @When I change its body as :body
     */
    public function iChangeItsLastNameAs($body)
    {
        $this->updatePage->changeBody($body);
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @When I delete topic with title :title
     */
    public function iDeleteTopicWithTitle($title)
    {
        $this->indexPage->deleteResourceOnPage(['title' => $title]);
    }

    /**
     * @Then /^there should be (\d+) topics in the list$/
     */
    public function iShouldSeeTopicsInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
    }

    /**
     * @Then the topic :topic should appear in the website
     * @Then I should see the topic :topic in the list
     */
    public function theTopicShould(Topic $topic)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['title' => $topic->getTitle()]));
    }

    /**
     * @Then this topic with title :title should appear in the website
     */
    public function thisTopicWithTitleShouldAppearInTheWebsite($title)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['title' => $title]));
    }

    /**
     * @Then this topic body should be :body
     */
    public function thisTopicBodyShouldBe($body)
    {
        $this->assertElementValue('body', $body);
    }

    /**
     * @Then there should not be :title topic anymore
     */
    public function thereShouldBeNoTopicAnymore($title)
    {
        Assert::false($this->indexPage->isSingleResourceOnPage(['title' => $title]));
    }

    /**
     * @param string $element
     * @param string $value
     */
    private function assertElementValue($element, $value)
    {
        Assert::true(
            $this->updatePage->hasResourceValues([$element => $value]),
            sprintf('Topic should have %s with %s value.', $element, $value)
        );
    }
}
