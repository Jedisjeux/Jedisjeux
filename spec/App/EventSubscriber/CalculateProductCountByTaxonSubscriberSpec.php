<?php

namespace spec\App\EventSubscriber;

use App\Entity\Product;
use App\Entity\Taxon;
use App\Event\ProductEvents;
use App\EventSubscriber\CalculateProductCountByTaxonSubscriber;
use App\Updater\ProductCountByTaxonUpdater;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class CalculateProductCountByTaxonSubscriberSpec extends ObjectBehavior
{
    function let(ProductCountByTaxonUpdater $updater)
    {
        $this->beConstructedWith($updater);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CalculateProductCountByTaxonSubscriber::class);
    }

    function it_subscribes_to_product_events()
    {
        $this::getSubscribedEvents()->shouldReturn([
            ProductEvents::PRE_CREATE => 'onProductCreate',
            ProductEvents::PRE_UPDATE => 'onProductUpdate',
        ]);
    }

    function it_updates_product_main_taxon_product_create_event(
        GenericEvent $event,
        ProductCountByTaxonUpdater $updater,
        Product $product,
        Taxon $taxon
    ): void {
        $event->getSubject()->willReturn($product);
        $product->getMainTaxon()->willReturn($taxon);
        $product->getTaxons()->willReturn(new ArrayCollection());

        $updater->update($taxon)->shouldBeCalled();

        $this->onProductCreate($event);
    }

    function it_updates_product_main_taxon_product_update_event(
        GenericEvent $event,
        ProductCountByTaxonUpdater $updater,
        Product $product,
        Taxon $taxon
    ): void {
        $event->getSubject()->willReturn($product);
        $product->getMainTaxon()->willReturn($taxon);
        $product->getTaxons()->willReturn(new ArrayCollection());

        $updater->update($taxon)->shouldBeCalled();

        $this->onProductUpdate($event);
    }

    function it_updates_each_product_taxon_on_product_create_event(
        GenericEvent $event,
        ProductCountByTaxonUpdater $updater,
        Product $product,
        Taxon $taxon1,
        Taxon $taxon2
    ): void {
        $event->getSubject()->willReturn($product);
        $product->getMainTaxon()->willReturn(null);
        $product->getTaxons()->willReturn(new ArrayCollection([
            $taxon1->getWrappedObject(),
            $taxon2->getWrappedObject(),
        ]));

        $updater->update($taxon1)->shouldBeCalled();
        $updater->update($taxon2)->shouldBeCalled();

        $this->onProductCreate($event);
    }

    function it_updates_each_product_taxon_on_product_update_event(
        GenericEvent $event,
        ProductCountByTaxonUpdater $updater,
        Product $product,
        Taxon $taxon1,
        Taxon $taxon2
    ): void {
        $event->getSubject()->willReturn($product);
        $product->getMainTaxon()->willReturn(null);
        $product->getTaxons()->willReturn(new ArrayCollection([
            $taxon1->getWrappedObject(),
            $taxon2->getWrappedObject(),
        ]));

        $updater->update($taxon1)->shouldBeCalled();
        $updater->update($taxon2)->shouldBeCalled();

        $this->onProductUpdate($event);
    }
}
