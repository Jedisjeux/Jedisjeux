<?php

namespace spec\AppBundle\EventSubscriber;

use AppBundle\Entity\Product;
use AppBundle\Entity\Taxon;
use AppBundle\EventSubscriber\CalculateProductCountByTaxonSubscriber;
use AppBundle\Updater\ProductCountByTaxonUpdater;
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

    function it_updates_for_main_taxon(
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

    function it_updates_for_each_taxon(
        GenericEvent $event,
        ProductCountByTaxonUpdater $updater,
        Product $product
    ): void {
        $taxons = new ArrayCollection();

        $taxon1 = new Taxon();
        $taxons->add($taxon1);

        $taxon2 = new Taxon();
        $taxons->add($taxon2);

        $event->getSubject()->willReturn($product);
        $product->getMainTaxon()->willReturn(null);
        $product->getTaxons()->willReturn($taxons);

        $updater->update($taxon1)->shouldBeCalled();
        $updater->update($taxon2)->shouldBeCalled();

        $this->onProductCreate($event);
    }
}
