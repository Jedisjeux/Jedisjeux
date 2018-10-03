<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\ArticleReview;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Review\Model\Review;
use Sylius\Component\Review\Model\ReviewInterface;

class ArticleReviewSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ArticleReview::class);
    }

    function it_extends_review()
    {
        $this->shouldBeAnInstanceOf(Review::class);
    }

    function its_status_is_accepted_by_default()
    {
        $this->getStatus()->shouldBeEqualTo(ReviewInterface::STATUS_ACCEPTED);
    }

    function its_title_is_null_by_default()
    {
        $this->getTitle()->shouldReturn(null);
    }
}
