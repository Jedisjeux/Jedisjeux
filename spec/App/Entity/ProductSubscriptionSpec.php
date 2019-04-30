<?php

namespace spec\App\Entity;

use App\Entity\CustomerInterface;
use App\Entity\ProductInterface;
use App\Entity\ProductSubscription;
use App\Entity\SubscribableInterface;
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

    function it_has_no_subject_by_default(): void
    {
        $this->getSubject()->shouldReturn(null);
    }

    function its_subject_is_mutable(ProductInterface $product): void
    {
        $this->setSubject($product);
        $this->getSubject()->shouldReturn($product);
    }

    function it_throws_an_invalid_argument_exception_when_subject_is_not_a_product(SubscribableInterface $subject)
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('setSubject', [$subject]);
    }

    function it_follows_articles_by_default()
    {
        $this->hasOption(ProductSubscription::OPTION_FOLLOW_ARTICLES)->shouldReturn(true);
    }

    function it_can_not_follow_articles()
    {
        $this->removeOption(ProductSubscription::OPTION_FOLLOW_ARTICLES);
        $this->hasOption(ProductSubscription::OPTION_FOLLOW_ARTICLES)->shouldReturn(false);
    }

    function it_follows_reviews_by_default()
    {
        $this->hasOption(ProductSubscription::OPTION_FOLLOW_REVIEWS)->shouldReturn(true);
    }

    function it_can_not_follow_reviews()
    {
        $this->removeOption(ProductSubscription::OPTION_FOLLOW_REVIEWS);
        $this->hasOption(ProductSubscription::OPTION_FOLLOW_REVIEWS)->shouldReturn(false);
    }

    function it_follows_game_plays_by_default()
    {
        $this->hasOption(ProductSubscription::OPTION_FOLLOW_GAME_PLAYS)->shouldReturn(true);
    }

    function it_can_not_follow_game_plays()
    {
        $this->removeOption(ProductSubscription::OPTION_FOLLOW_GAME_PLAYS);
        $this->hasOption(ProductSubscription::OPTION_FOLLOW_GAME_PLAYS)->shouldReturn(false);
    }

    function it_follows_videos_by_default()
    {
        $this->hasOption(ProductSubscription::OPTION_FOLLOW_VIDEOS)->shouldReturn(true);
    }

    function it_can_not_follow_videos()
    {
        $this->removeOption(ProductSubscription::OPTION_FOLLOW_VIDEOS);
        $this->hasOption(ProductSubscription::OPTION_FOLLOW_VIDEOS)->shouldReturn(false);
    }

    function it_follows_files_by_default()
    {
        $this->hasOption(ProductSubscription::OPTION_FOLLOW_FILES)->shouldReturn(true);
    }

    function it_can_not_follow_files()
    {
        $this->removeOption(ProductSubscription::OPTION_FOLLOW_FILES);
        $this->hasOption(ProductSubscription::OPTION_FOLLOW_FILES)->shouldReturn(false);
    }
}
