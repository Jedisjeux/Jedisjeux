<?php

namespace spec\AppBundle\EventSubscriber;

use AppBundle\Entity\Person;
use AppBundle\Entity\Product;
use AppBundle\EventSubscriber\CalculateProductCountByPersonSubscriber;
use AppBundle\Updater\ProductCountByPersonUpdater;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
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

    function it_updates_for_each_designer(
        GenericEvent $event,
        ProductCountByPersonUpdater $updater,
        Product $product
    ): void {
        $designers = new ArrayCollection();

        $designer1 = new Person;
        $designers->add($designer1);

        $designer2 = new Person;
        $designers->add($designer2);

        $event->getSubject()->willReturn($product);
        $product->getDesigners()->willReturn($designers);
        $product->getArtists()->willReturn(new ArrayCollection());
        $product->getPublishers()->willReturn(new ArrayCollection());

        $updater->update($designer1)->shouldBeCalled();
        $updater->update($designer2)->shouldBeCalled();

        $this->onProductCreate($event);
    }

    function it_updates_for_each_artist(
        GenericEvent $event,
        ProductCountByPersonUpdater $updater,
        Product $product
    ): void {
        $artists = new ArrayCollection();

        $artist1 = new Person;
        $artists->add($artist1);

        $artist2 = new Person;
        $artists->add($artist2);

        $event->getSubject()->willReturn($product);
        $product->getDesigners()->willReturn(new ArrayCollection());
        $product->getArtists()->willReturn($artists);
        $product->getPublishers()->willReturn(new ArrayCollection());

        $updater->update($artist1)->shouldBeCalled();
        $updater->update($artist2)->shouldBeCalled();

        $this->onProductCreate($event);
    }

    function it_updates_for_each_publisher(
        GenericEvent $event,
        ProductCountByPersonUpdater $updater,
        Product $product
    ): void {
        $publishers = new ArrayCollection();

        $publisher1 = new Person;
        $publishers->add($publisher1);

        $publisher2 = new Person;
        $publishers->add($publisher2);

        $event->getSubject()->willReturn($product);
        $product->getDesigners()->willReturn(new ArrayCollection());
        $product->getArtists()->willReturn(new ArrayCollection());
        $product->getPublishers()->willReturn($publishers);

        $updater->update($publisher1)->shouldBeCalled();
        $updater->update($publisher2)->shouldBeCalled();

        $this->onProductCreate($event);
    }
}
