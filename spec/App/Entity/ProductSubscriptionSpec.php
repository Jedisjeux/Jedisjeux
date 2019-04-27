<?php

namespace spec\App\Entity;

use App\Entity\CustomerInterface;
use App\Entity\ProductInterface;
use App\Entity\ProductSubscription;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\ResourceInterface;

class ProductSubscriptionSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductSubscription::class);
    }

    function it_implements_resource_interface(): void
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_has_no_id_by_default(): void
    {
        $this->getId()->shouldReturn(null);
    }

    function it_has_no_subscriber_by_defaut(): void
    {
        $this->getSubscriber()->shouldReturn(null);
    }

    function its_subscriber_is_mutable(CustomerInterface $subscriber): void
    {
        $this->setSubscriber($subscriber);
        $this->getSubscriber()->shouldReturn($subscriber);
    }

    function it_has_no_product_by_default(): void
    {
        $this->getProduct()->shouldReturn(null);
    }

    function its_product_is_mutable(ProductInterface $product): void
    {
        $this->setProduct($product);
        $this->getProduct()->shouldReturn($product);
    }

    function it_follows_articles_by_default()
    {
        $this->isFollowArticles()->shouldReturn(true);
    }

    function it_can_not_follow_articles()
    {
        $this->setFollowArticles(false);
        $this->isFollowArticles()->shouldReturn(false);
    }

    function it_follows_reviews_by_default()
    {
        $this->isFollowReviews()->shouldReturn(true);
    }

    function it_can_not_follow_reviews()
    {
        $this->setFollowReviews(false);
        $this->isFollowReviews()->shouldReturn(false);
    }

    function it_follows_game_plays_by_default()
    {
        $this->isFollowGamePlays()->shouldReturn(true);
    }

    function it_can_not_follow_game_plays()
    {
        $this->setFollowGamePlays(false);
        $this->isFollowGamePlays()->shouldReturn(false);
    }

    function it_follows_videos_by_default()
    {
        $this->isFollowVideos()->shouldReturn(true);
    }

    function it_can_not_follow_videos()
    {
        $this->setFollowVideos(false);
        $this->isFollowVideos()->shouldReturn(false);
    }

    function it_follows_files_by_default()
    {
        $this->isFollowFiles()->shouldReturn(true);
    }

    function it_can_not_follow_files()
    {
        $this->setFollowFiles(false);
        $this->isFollowFiles()->shouldReturn(false);
    }
}
