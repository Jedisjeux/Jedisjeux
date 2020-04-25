<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Behat\Context\Domain;

use App\Entity\Article;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Webmozart\Assert\Assert;

final class ArticleContext implements Context
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Then /^(this article) should have one comment$/
     */
    public function thisArticleShouldHaveOneComment(Article $article, int $amountOfComments = 1): void
    {
        $this->entityManager->refresh($article);

        $topic = $article->getTopic();

        Assert::eq($topic->getPostCount(), $amountOfComments);
    }
}
