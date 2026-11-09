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
use App\Repository\ArticleRepository;
use App\Tests\Behat\Page\Backend\Article\CreateForProductPage;
use App\Tests\Behat\Page\Backend\Article\CreateReviewForProductPage;
use App\Tests\Behat\Page\Backend\Article\IndexForProductPage;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Webmozart\Assert\Assert;

final class ManagingProductArticlesContext implements Context
{
    public function __construct(
        private readonly IndexForProductPage $indexPage,
        private readonly CreateForProductPage $createPage,
        private readonly CreateReviewForProductPage $createReviewPage,
        #[Autowire(service: 'app.repository.article')]
        private readonly ArticleRepository $articleRepository,
    ) {
    }

    /**
     * @When /^I want to browse the articles of (this product)$/
     */
    public function iWantToBrowseTheArticlesOfThisProduct(ProductInterface $product): void
    {
        $this->indexPage->open(['productId' => $product->getId()]);
    }

    /**
     * @Given /^I want to write an article about (this product)$/
     */
    public function iWantToWriteAnArticleAboutThisProduct(ProductInterface $product): void
    {
        $this->createPage->open(['productId' => $product->getId()]);
    }

    /**
     * @Given /^I want to write a review of (this product)$/
     */
    public function iWantToWriteAReviewOfThisProduct(ProductInterface $product): void
    {
        $this->createReviewPage->open(['productId' => $product->getId()]);
    }

    /**
     * @When I specify its title as :title
     */
    public function iSpecifyItsTitleAs(string $title): void
    {
        $this->createPage->specifyTitle($title);
    }

    /**
     * @When I add this article
     */
    public function iAddThisArticle(): void
    {
        $this->createPage->create();
    }

    /**
     * @When I add this review
     */
    public function iAddThisReview(): void
    {
        $this->createReviewPage->create();
    }

    /**
     * @Then /^there should be (\d+) articles? about this product in the list$/
     */
    public function thereShouldBeArticlesAboutThisProductInTheList(int $number): void
    {
        Assert::same($this->indexPage->countItems(), $number);
    }

    /**
     * @Then /^the article "([^"]+)" should be about (this product)$/
     */
    public function theArticleShouldBeAboutThisProduct(string $title, ProductInterface $product): void
    {
        /** @var Article|null $article */
        $article = $this->articleRepository->findOneBy(['title' => $title]);

        Assert::notNull($article, sprintf('Article "%s" has not been found.', $title));
        Assert::same($article->getProduct()?->getId(), $product->getId());
    }
}
