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

namespace App\Tests\Unit\Entity;

use App\Entity\ProductReview;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Review\Model\Review;
use Sylius\Component\Review\Model\ReviewInterface;

final class ProductReviewTest extends TestCase
{
    private ProductReview $review;

    protected function setUp(): void
    {
        $this->review = new ProductReview();
    }

    public function testItExtendsAReviewModel(): void
    {
        $this->assertInstanceOf(Review::class, $this->review);
    }

    public function testItHasAGeneratedCodeByDefault(): void
    {
        $this->assertNotNull($this->review->getCode());
    }

    public function testItsTitleIsNullByDefault(): void
    {
        $this->assertNull($this->review->getTitle());
    }

    public function testItsStatusIsAcceptedByDefault(): void
    {
        $this->assertSame(ReviewInterface::STATUS_ACCEPTED, $this->review->getStatus());
    }

    public function testItInitializesCreationDateByDefault(): void
    {
        $this->assertInstanceOf(\DateTimeInterface::class, $this->review->getCreatedAt());
    }

    public function testItsCreationDateIsMutable(): void
    {
        $date = new \DateTime();

        $this->review->setCreatedAt($date);

        $this->assertSame($date, $this->review->getCreatedAt());
    }
}
