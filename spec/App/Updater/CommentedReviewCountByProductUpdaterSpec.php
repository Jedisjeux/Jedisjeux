<?php

namespace spec\App\Updater;

use App\Calculator\CommentedReviewCountByProductCalculator;
use App\Entity\Product;
use PhpSpec\ObjectBehavior;

class CommentedReviewCountByProductUpdaterSpec extends ObjectBehavior
{
    function let(CommentedReviewCountByProductCalculator $calculator)
    {
        $this->beConstructedWith($calculator);
    }

    function it_updates_product_with_rating_count(CommentedReviewCountByProductCalculator $calculator, Product $product)
    {
        $calculator->calculate($product)->willReturn(5);

        $product->setCommentedReviewCount(5)->shouldBeCalled();

        $this->update($product);
    }
}
