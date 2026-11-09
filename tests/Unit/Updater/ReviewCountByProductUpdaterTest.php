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

namespace App\Tests\Unit\Updater;

use App\Calculator\ReviewCountByProductCalculator;
use App\Entity\Product;
use App\Updater\ReviewCountByProductUpdater;
use PHPUnit\Framework\TestCase;

final class ReviewCountByProductUpdaterTest extends TestCase
{
    public function testItUpdatesProductWithReviewCount(): void
    {
        $product = $this->createMock(Product::class);
        $calculator = $this->createStub(ReviewCountByProductCalculator::class);
        $calculator->method('calculate')->with($product)->willReturn(5);

        $product->expects($this->once())->method('setReviewCount')->with(5);

        (new ReviewCountByProductUpdater($calculator))->update($product);
    }
}
