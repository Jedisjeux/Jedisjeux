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

use App\Entity\ProductInterface;

class ReviewCountByProductCalculator
{
    public function calculate(ProductInterface $product): int
    {
        return $product->getReviews()->count();
    }
}
