<?php

namespace spec\App\Calculator;

use App\Entity\Person;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Product\Model\ProductInterface;

class ProductCountByPersonCalculatorSpec extends ObjectBehavior
{
    function it_calculates_product_count_for_a_designer(
        Person $person,
        ProductInterface $firstProduct,
        ProductInterface $secondProduct
    ): void {
        $person->getDesignerProducts()->willReturn(new ArrayCollection([
            $firstProduct->getWrappedObject(),
            $secondProduct->getWrappedObject(),
        ]));

        $this->calculateAsDesigner($person)->shouldReturn(2);
    }

    function it_calculates_product_count_for_an_artist(
        Person $person,
        ProductInterface $firstProduct,
        ProductInterface $secondProduct
    ): void {
        $person->getArtistProducts()->willReturn(new ArrayCollection([
            $firstProduct->getWrappedObject(),
            $secondProduct->getWrappedObject(),
        ]));

        $this->calculateAsArtist($person)->shouldReturn(2);
    }

    function it_calculates_product_count_for_a_publisher(
        Person $person,
        ProductInterface $firstProduct,
        ProductInterface $secondProduct
    ): void {
        $person->getPublisherProducts()->willReturn(new ArrayCollection([
            $firstProduct->getWrappedObject(),
            $secondProduct->getWrappedObject(),
        ]));

        $this->calculateAsPublisher($person)->shouldReturn(2);
    }
}
