<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Updater;

use App\Calculator\ReviewCountByProductCalculator;
use App\Entity\ProductInterface;

class ReviewCountByProductUpdater
{
    /**
     * @var ReviewCountByProductCalculator
     */
    private $calculator;

    public function __construct(ReviewCountByProductCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function update(ProductInterface $product)
    {
        $ratingCount = $this->calculator->calculate($product);
        $product->setReviewCount($ratingCount);
    }
}
