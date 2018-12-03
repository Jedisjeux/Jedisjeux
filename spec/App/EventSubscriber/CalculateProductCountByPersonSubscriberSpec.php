<?php

namespace spec\App\EventSubscriber;

use App\Entity\Person;
use App\Entity\Product;
use App\Event\ProductEvents;
use App\EventSubscriber\CalculateProductCountByPersonSubscriber;
use App\Updater\ProductCountByPersonUpdater;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\GenericEvent;

class CalculateProductCountByPersonSubscriberSpec extends ObjectBehavior
{
    function let(ProductCountByPersonUpdater $updater)
    {
        $this->beConstructedWith($updater);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CalculateProductCountByPersonSubscriber::class);
    }

    function it_subscribes_to_post_create_event()
    {
        $this::getSubscribedEvents()->shouldReturn([
            ProductEvents::PRE_CREATE => 'onProductCreate',
            ProductEvents::PRE_UPDATE => 'onProductUpdate',
        ]);
    }

    function it_updates_for_each_designer_on_product_create_event(
        GenericEvent $event,
        ProductCountByPersonUpdater $updater,
        Product $product,
        Person $designer1,
        Person $designer2
    ): void {
        $event->getSubject()->willReturn($product);
        $product->getDesigners()->willReturn(new ArrayCollection([
            $designer1->getWrappedObject(),
            $designer2->getWrappedObject(),
        ]));
        $product->getArtists()->willReturn(new ArrayCollection());
        $product->getPublishers()->willReturn(new ArrayCollection());

        $updater->update($designer1)->shouldBeCalled();
        $updater->update($designer2)->shouldBeCalled();

        $this->onProductCreate($event);
    }

    function it_updates_for_each_designer_on_product_update_event(
        GenericEvent $event,
        ProductCountByPersonUpdater $updater,
        Product $product,
        Person $designer1,
        Person $designer2
    ): void {
        $event->getSubject()->willReturn($product);
        $product->getDesigners()->willReturn(new ArrayCollection([
            $designer1->getWrappedObject(),
            $designer2->getWrappedObject(),
        ]));
        $product->getArtists()->willReturn(new ArrayCollection());
        $product->getPublishers()->willReturn(new ArrayCollection());

        $updater->update($designer1)->shouldBeCalled();
        $updater->update($designer2)->shouldBeCalled();

        $this->onProductUpdate($event);
    }

    function it_updates_for_each_artist_on_product_create_event(
        GenericEvent $event,
        ProductCountByPersonUpdater $updater,
        Product $product,
        Person $artist1,
        Person $artist2
    ): void {
        $event->getSubject()->willReturn($product);
        $product->getDesigners()->willReturn(new ArrayCollection());
        $product->getArtists()->willReturn(new ArrayCollection([
            $artist1->getWrappedObject(),
            $artist2->getWrappedObject(),
        ]));
        $product->getPublishers()->willReturn(new ArrayCollection());

        $updater->update($artist1)->shouldBeCalled();
        $updater->update($artist2)->shouldBeCalled();

        $this->onProductCreate($event);
    }

    function it_updates_for_each_artist_on_product_update_event(
        GenericEvent $event,
        ProductCountByPersonUpdater $updater,
        Product $product,
        Person $artist1,
        Person $artist2
    ): void {
        $event->getSubject()->willReturn($product);
        $product->getDesigners()->willReturn(new ArrayCollection());
        $product->getArtists()->willReturn(new ArrayCollection([
            $artist1->getWrappedObject(),
            $artist2->getWrappedObject(),
        ]));
        $product->getPublishers()->willReturn(new ArrayCollection());

        $updater->update($artist1)->shouldBeCalled();
        $updater->update($artist2)->shouldBeCalled();

        $this->onProductUpdate($event);
    }

    function it_updates_for_each_publisher_on_product_create_event(
        GenericEvent $event,
        ProductCountByPersonUpdater $updater,
        Product $product,
        Person $publisher1,
        Person $publisher2
    ): void {
        $event->getSubject()->willReturn($product);
        $product->getDesigners()->willReturn(new ArrayCollection());
        $product->getArtists()->willReturn(new ArrayCollection());
        $product->getPublishers()->willReturn(new ArrayCollection([
            $publisher1->getWrappedObject(),
            $publisher2->getWrappedObject(),
        ]));

        $updater->update($publisher1)->shouldBeCalled();
        $updater->update($publisher2)->shouldBeCalled();

        $this->onProductCreate($event);
    }

    function it_updates_for_each_publisher_on_product_update_event(
        GenericEvent $event,
        ProductCountByPersonUpdater $updater,
        Product $product,
        Person $publisher1,
        Person $publisher2
    ): void {
        $event->getSubject()->willReturn($product);
        $product->getDesigners()->willReturn(new ArrayCollection());
        $product->getArtists()->willReturn(new ArrayCollection());
        $product->getPublishers()->willReturn(new ArrayCollection([
            $publisher1->getWrappedObject(),
            $publisher2->getWrappedObject(),
        ]));

        $updater->update($publisher1)->shouldBeCalled();
        $updater->update($publisher2)->shouldBeCalled();

        $this->onProductUpdate($event);
    }
}
