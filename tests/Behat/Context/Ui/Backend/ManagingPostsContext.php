<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Ui\Backend;

use App\Tests\Behat\Page\Backend\Post\IndexPage;
use App\Tests\Behat\Page\Backend\Post\UpdatePage;
use App\Tests\Behat\Service\Resolver\CurrentPageResolverInterface;
use App\Entity\Customer;
use App\Entity\Post;
use App\Entity\Topic;
use Behat\Behat\Context\Context;
use Sylius\Component\Customer\Model\CustomerInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ManagingPostsContext implements Context
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
     * @param IndexPage                    $indexPage
     * @param UpdatePage                   $updatePage
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
     * @When I want to browse posts
     */
    public function iWantToBrowsePosts()
    {
        $this->indexPage->open();
    }

    /**
     * @When /^I want to edit (this post)$/
     */
    public function iWantToEditThePost(Post $post)
    {
        $this->updatePage->open(['id' => $post->getId()]);
    }

    /**
     * @When I change its body as :body
     */
    public function iChangeItsBodyAs($body)
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
     * @When /^I delete (this post)$/
     */
    public function iDeletePost(Post $post)
    {
        $this->indexPage->deleteResourceOnPage([
            'topic' => $post->getTopic(),
            'author' => $post->getAuthor(),
        ]);
    }

    /**
     * @Then /^there should be (\d+) posts in the list$/
     */
    public function iShouldSeePostsInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
    }

    /**
     * @Then I should see the post added by customer :customer on topic :topic in the list
     */
    public function thePostShould(CustomerInterface $customer, Topic $topic)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage([
            'author' => $customer,
            'topic' => $topic->getTitle(),
        ]));
    }

    /**
     * @Then this post with title :title should appear in the website
     */
    public function thisPostWithTitleShouldAppearInTheWebsite($title)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['title' => $title]));
    }

    /**
     * @Then this post body should be :body
     */
    public function thisPostBodyShouldBe($body)
    {
        $this->assertElementValue('body', $body);
    }

    /**
     * @Then there should not be post added by customer :customer on topic :topic anymore
     */
    public function thereShouldBeNoPostAnymore(CustomerInterface $customer, Topic $topic)
    {
        Assert::false($this->indexPage->isSingleResourceOnPage([
            'author' => $customer,
            'topic' => $topic,
        ]));
    }

    /**
     * @param string $element
     * @param string $value
     */
    private function assertElementValue($element, $value)
    {
        Assert::true(
            $this->updatePage->hasResourceValues([$element => $value]),
            sprintf('Post should have %s with %s value.', $element, $value)
        );
    }
}
