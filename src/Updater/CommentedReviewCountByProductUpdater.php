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
    /**
     * @var CommentedReviewCountByProductCalculator
     */
    private $calculator;

    /**
     * @param CommentedReviewCountByProductCalculator $calculator
     */
    public function __construct(CommentedReviewCountByProductCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @param Product $product
     */
    public function update(Product $product)
    {
        $ratingCount = $this->calculator->calculate($product);
        $product->setCommentedReviewCount($ratingCount);
    }
}
