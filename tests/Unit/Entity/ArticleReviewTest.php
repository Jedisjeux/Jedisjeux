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

use App\Entity\ArticleReview;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Review\Model\Review;
use Sylius\Component\Review\Model\ReviewInterface;

final class ArticleReviewTest extends TestCase
{
    private ArticleReview $sut;

    protected function setUp(): void
    {
        $this->sut = new ArticleReview();
    }

    public function testItExtendsReview(): void
    {
        $this->assertInstanceOf(Review::class, $this->sut);
    }

    public function testItsStatusIsAcceptedByDefault(): void
    {
        $this->assertEquals(ReviewInterface::STATUS_ACCEPTED, $this->sut->getStatus());
    }

    public function testItsTitleIsNullByDefault(): void
    {
        $this->assertNull($this->sut->getTitle());
    }

    public function testItInitializesCreationDateByDefault(): void
    {
        $this->assertInstanceOf(\DateTimeInterface::class, $this->sut->getCreatedAt());
    }

    public function testItsCreationDateIsMutable(): void
    {
        $date = new \DateTime();

        $this->sut->setCreatedAt($date);
        $this->assertSame($date, $this->sut->getCreatedAt());
    }
}
