<?php

namespace spec\App\Calculator;

use App\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Review\Model\ReviewInterface;

class CommentedReviewCountByProductCalculatorSpec extends ObjectBehavior
{
    function it_calculates_commented_review_count_for_a_product(
        Product $product,
        ReviewInterface $firstReview,
        ReviewInterface $secondReview
    ): void {
        $product->getCommentedReviews()->willReturn(new ArrayCollection([
            $firstReview->getWrappedObject(),
            $secondReview->getWrappedObject(),
        ]));

        $this->calculate($product)->shouldReturn(2);
    }
}
