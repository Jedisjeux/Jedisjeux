<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Updater;

use App\Calculator\CommentedReviewCountByProductCalculator;
use App\Entity\Product;

class CommentedReviewCountByProductUpdater
{
    public function __construct(private CommentedReviewCountByProductCalculator $calculator)
    {
    }

    public function update(Product $product): void
    {
        $ratingCount = $this->calculator->calculate($product);
        $product->setCommentedReviewCount($ratingCount);
    }
}
