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
use App\Entity\Product;
use App\Entity\ProductInterface;

class ReviewCountByProductUpdater
{
    /**
     * @var ReviewCountByProductCalculator
     */
    private $calculator;

    /**
     * @param ReviewCountByProductCalculator $calculator
     */
    public function __construct(ReviewCountByProductCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @param ProductInterface $product
     */
    public function update(ProductInterface $product)
    {
        $ratingCount = $this->calculator->calculate($product);
        $product->setReviewCount($ratingCount);
    }
}
