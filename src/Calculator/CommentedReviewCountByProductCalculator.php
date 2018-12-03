<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Calculator;

use App\Entity\Product;

class CommentedReviewCountByProductCalculator
{
    /**
     * @param Product $product
     *
     * @return int
     */
    public function calculate(Product $product): int
    {
        return $product->getCommentedReviews()->count();
    }
}
