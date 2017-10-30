<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\ProductReview;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Review\Model\Review;
use Sylius\Component\Review\Model\ReviewInterface;

class ProductReviewSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ProductReview::class);
    }

    function it_extends_a_review_model(): void
    {
        $this->shouldHaveType(Review::class);
    }

    function it_has_a_generated_code_by_default()
    {
        $this->getCode()->shouldNotBeNull();
    }

    function its_title_is_empty_by_default()
    {
        $this->getTitle()->shouldReturn("");
    }

    function its_status_is_accepted_by_default()
    {
        $this->getStatus()->shouldReturn(ReviewInterface::STATUS_ACCEPTED);
    }
}
