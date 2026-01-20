<?php

namespace spec\App\Calculator;

use App\Calculator\ReviewAverageCalculator;
use App\Repository\ProductReviewRepository;
use PhpSpec\ObjectBehavior;

class ReviewAverageCalculatorSpec extends ObjectBehavior
{
    function let (ProductReviewRepository $productReviewRepository): void
    {
        $this->beConstructedWith($productReviewRepository);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ReviewAverageCalculator::class);
    }

    function it_returns_average_for_all_reviews(ProductReviewRepository $productReviewRepository): void
    {
        $productReviewRepository->findOverallAverage()->willReturn(7.2);

        $this->calculate()->shouldReturn(7.2);
    }
}
