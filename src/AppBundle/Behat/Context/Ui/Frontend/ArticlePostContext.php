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

use AppBundle\Behat\Page\Frontend\Post\CreateForArticlePage;
use AppBundle\Behat\Page\Frontend\Post\UpdateForArticlePage;
use AppBundle\Entity\Article;
use AppBundle\Entity\Post;
use Behat\Behat\Context\Context;

class ArticlePostContext implements Context
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
     * @param CreateForArticlePage $createPage
     * @param UpdateForArticlePage $updatePage
     */
    public function __construct(CreateForArticlePage $createPage, UpdateForArticlePage $updatePage)
    {
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
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
