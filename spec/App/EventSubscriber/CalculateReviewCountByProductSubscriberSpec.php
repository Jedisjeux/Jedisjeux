<?php

namespace spec\App\EventSubscriber;

use App\Entity\Product;
use App\Event\ProductEvents;
use App\Updater\ReviewCountByProductUpdater;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\GenericEvent;

class CalculateReviewCountByProductSubscriberSpec extends ObjectBehavior
{
    function let(ReviewCountByProductUpdater $updater)
    {
        $this->beConstructedWith($updater);
    }

    function it_subscribes_to_product_events()
    {
        $this::getSubscribedEvents()->shouldReturn([
            ProductEvents::PRE_CREATE => 'onProductCreate',
            ProductEvents::PRE_UPDATE => 'onProductUpdate',
        ]);
    }

    function it_updates_rating_count_on_product_create_event(
        GenericEvent $event,
        ReviewCountByProductUpdater $updater,
        Product $product
    ): void {
        $event->getSubject()->willReturn($product);

        $updater->update($product)->shouldBeCalled();

        $this->onProductCreate($event);
    }

    function it_updates_rating_count_on_product_update_event(
        GenericEvent $event,
        ReviewCountByProductUpdater $updater,
        Product $product
    ): void {
        $event->getSubject()->willReturn($product);

        $updater->update($product)->shouldBeCalled();

        $this->onProductUpdate($event);
    }
}
