<?php

namespace spec\App\Entity;

use App\Entity\ProductReview;
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

    function its_title_is_null_by_default()
    {
        $this->getTitle()->shouldReturn(null);
    }

    function its_status_is_accepted_by_default()
    {
        $this->getStatus()->shouldReturn(ReviewInterface::STATUS_ACCEPTED);
    }

    function it_initializes_creation_date_by_default(): void
    {
        $this->getCreatedAt()->shouldHaveType(\DateTimeInterface::class);
    }

    function its_creation_date_is_mutable(\DateTime $date): void
    {
        $this->setCreatedAt($date);
        $this->getCreatedAt()->shouldReturn($date);
    }
}
