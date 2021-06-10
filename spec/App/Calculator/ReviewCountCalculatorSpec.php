<?php

namespace spec\App\Calculator;

use App\Calculator\ReviewCountCalculator;
use App\Repository\ProductReviewRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReviewCountCalculatorSpec extends ObjectBehavior
{
    function let (ProductReviewRepository $productReviewRepository): void
    {
        $this->beConstructedWith($productReviewRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ReviewCountCalculator::class);
    }

    function it_returns_total_number_of_reviews(ProductReviewRepository $productReviewRepository): void
    {
        $productReviewRepository->countProductReviews()->willReturn(70);

        $this->calculate()->shouldReturn(70);
    }
}
