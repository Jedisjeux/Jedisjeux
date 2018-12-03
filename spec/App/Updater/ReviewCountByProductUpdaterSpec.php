<?php

namespace spec\App\Updater;

use App\Entity\Product;
use App\Calculator\ReviewCountByProductCalculator;
use PhpSpec\ObjectBehavior;

class ReviewCountByProductUpdaterSpec extends ObjectBehavior
{
    function let(ReviewCountByProductCalculator $calculator)
    {
        $this->beConstructedWith($calculator);
    }

    function it_updates_product_with_rating_count(ReviewCountByProductCalculator $calculator, Product $product)
    {
        $calculator->calculate($product)->willReturn(5);

        $product->setReviewCount(5)->shouldBeCalled();

        $this->update($product);
    }
}
