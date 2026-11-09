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

use App\Calculator\CommentedReviewCountByProductCalculator;
use App\Entity\Product;
use App\Updater\CommentedReviewCountByProductUpdater;
use PHPUnit\Framework\TestCase;

final class CommentedReviewCountByProductUpdaterTest extends TestCase
{
    public function testItUpdatesProductWithCommentedReviewCount(): void
    {
        $product = $this->createMock(Product::class);
        $calculator = $this->createStub(CommentedReviewCountByProductCalculator::class);
        $calculator->method('calculate')->with($product)->willReturn(5);

        $product->expects($this->once())->method('setCommentedReviewCount')->with(5);

        (new CommentedReviewCountByProductUpdater($calculator))->update($product);
    }
}
