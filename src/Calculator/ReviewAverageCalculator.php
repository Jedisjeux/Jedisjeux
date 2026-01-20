<?php
/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Calculator;

use App\Repository\ProductReviewRepository;

class ReviewAverageCalculator
{
    /** @var ProductReviewRepository */
    private $productReviewRepository;

    public function __construct(ProductReviewRepository $productReviewRepository)
    {
        $this->productReviewRepository = $productReviewRepository;
    }

    public function calculate(): float
    {
        return $this->productReviewRepository->findOverallAverage();
    }
}
