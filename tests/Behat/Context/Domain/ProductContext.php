<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Context\Domain;

use App\Entity\ProductInterface;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Webmozart\Assert\Assert;

final class ProductContext implements Context
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Then /^(this product) should have one review$/
     */
    public function thisProductShouldHaveOneReview(ProductInterface $product, int $amountOfReviews = 1)
    {
        $this->entityManager->refresh($product);

        Assert::eq($product->getReviewCount(), $amountOfReviews);
    }

    /**
     * @Then /^(this product) should have an average rating of (\d+) points$/
     */
    public function thisProductShouldHaveAnAverageRatingOfPoints(ProductInterface $product, int $averageRating): void
    {
        $this->entityManager->refresh($product);

        Assert::eq($product->getAverageRating(), $averageRating);
    }
}
