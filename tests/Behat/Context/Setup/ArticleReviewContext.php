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

namespace App\Tests\Behat\Context\Setup;

use App\Entity\Article;
use Behat\Behat\Context\Context;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Review\Factory\ReviewFactoryInterface;
use Sylius\Component\Review\Model\ReviewerInterface;
use Sylius\Component\Review\Model\ReviewInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class ArticleReviewContext implements Context
{
    public function __construct(
        #[Autowire(service: 'sylius.factory.article_review')]
        private readonly ReviewFactoryInterface $articleReviewFactory,
        #[Autowire(service: 'sylius.repository.article_review')]
        private readonly RepositoryInterface $articleReviewRepository,
    ) {
    }

    /**
     * @Given /^(customer "[^"]+") rated (this article) (\d+)$/
     * @Given /^(customer "[^"]+") also rated (this article) (\d+)$/
     */
    public function customerRatedThisArticle(CustomerInterface $customer, Article $article, int $rating): void
    {
        /** @var ReviewerInterface $customer */
        /** @var ReviewInterface $articleReview */
        $articleReview = $this->articleReviewFactory->createForSubjectWithReviewer($article, $customer);
        $articleReview->setTitle('Review');
        $articleReview->setComment('Nice article');
        $articleReview->setRating($rating);
        $article->addReview($articleReview);

        $this->articleReviewRepository->add($articleReview);
    }
}
