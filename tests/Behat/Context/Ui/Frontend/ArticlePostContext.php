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

use App\Tests\Behat\Page\Frontend\Article as ArticlePage;
use App\Tests\Behat\Page\Frontend\Post\CreateForArticlePage;
use App\Tests\Behat\Page\Frontend\Post\UpdateForArticlePage;
use App\Entity\Article;
use App\Entity\Post;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

final class ArticlePostContext implements Context
{
    /**
     * @var CreateForArticlePage
     */
    private $createPage;

    /**
     * @var UpdateForArticlePage
     */
    private $updatePage;

    /**
     * @var ArticlePage\ShowPage
     */
    private $articleShowPage;

    public function __construct(
        CreateForArticlePage $createPage,
        UpdateForArticlePage $updatePage,
        ArticlePage\ShowPage $articleShowPage
    ) {
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
        $this->articleShowPage = $articleShowPage;
    }

    /**
     * @Given /^I want to react on (this article)$/
     */
    public function iWantToReactOnArticle(Article $article)
    {
        $this->createPage->open(['articleSlug' => $article->getSlug()]);
    }

    /**
     * @Given /^I want to change (this comment)$/
     */
    public function iWantToChangePost(Post $post)
    {
        $this->updatePage->open([
            'id' => $post->getId(),
            'articleSlug' => $post->getTopic()->getArticle()->getSlug(),
        ]);
    }

    /**
     * @When /^I want to remove (this comment)$/
     */
    public function iWantToRemovePost(Post $post)
    {
        $this->articleShowPage->open(['slug' => $post->getTopic()->getArticle()->getSlug()]);

        $button = $this->articleShowPage->getRemoveButtonFromPostWithComment($post->getBody());
        Assert::notNull($button, 'Remove button was not found for this comment');

        $button->press();
    }

    /**
     * @When I leave a comment :comment
     * @When I do not leave any comment
     */
    public function iLeaveAComment($comment = null)
    {
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
}
