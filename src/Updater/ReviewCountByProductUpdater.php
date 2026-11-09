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

use App\Calculator\ReviewCountByProductCalculator;
use App\Entity\ProductInterface;

class ReviewCountByProductUpdater
{
    public function __construct(private ReviewCountByProductCalculator $calculator)
    {
    }

    public function update(ProductInterface $product): void
    {
        $ratingCount = $this->calculator->calculate($product);
        $product->setReviewCount($ratingCount);
    }
}
