<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Context\Ui\Backend;

use App\Entity\Article;
use App\Tests\Behat\Page\Backend\ArticleReview\CreatePage;
use App\Tests\Behat\Page\Backend\ArticleReview\IndexPage;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

final class ManagingArticleReviewsContext implements Context
{
    private ?Article $article = null;

    public function __construct(
        private readonly IndexPage $indexPage,
        private readonly CreatePage $createPage,
    ) {
    }

    /**
     * @When /^I want to browse the reviews of (this article)$/
     */
    public function iWantToBrowseTheReviewsOfThisArticle(Article $article): void
    {
        $this->article = $article;
        $this->indexPage->open(['articleId' => $article->getId()]);
    }

    /**
     * @Given /^I want to review (this article)$/
     */
    public function iWantToReviewThisArticle(Article $article): void
    {
        $this->article = $article;
        $this->createPage->open(['articleId' => $article->getId()]);
    }

    /**
     * @When I rate it :rating
     */
    public function iRateIt(int $rating): void
    {
        $this->createPage->rateIt($rating);
    }

    /**
     * @When I comment it :comment
     */
    public function iCommentIt(string $comment): void
    {
        $this->createPage->commentIt($comment);
    }

    /**
     * @When I add it
     */
    public function iAddIt(): void
    {
        $this->createPage->create();
    }

    /**
     * @When I delete the review of :author
     */
    public function iDeleteTheReviewOf(string $author): void
    {
        $this->indexPage->deleteResourceOnPage(['author' => $author]);
    }

    /**
     * @Then /^there should be (\d+) reviews? of this article in the list$/
     */
    public function thereShouldBeReviewsOfThisArticleInTheList(int $number): void
    {
        Assert::notNull($this->article);
        $this->indexPage->open(['articleId' => $this->article->getId()]);

        Assert::same($this->indexPage->countItems(), $number);
    }
}
