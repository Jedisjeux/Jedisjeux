<?php

namespace spec\App\Updater;

use App\Calculator\ProductCountByPersonCalculator;
use App\Entity\Person;
use PhpSpec\ObjectBehavior;

class ProductCountByPersonUpdaterSpec extends ObjectBehavior
{
    function let(ProductCountByPersonCalculator $calculator)
    {
        $this->beConstructedWith($calculator);
    }

    function it_updates_product_count_for_a_person(
        ProductCountByPersonCalculator $calculator,
        Person $person
    ): void {
        $calculator->calculateAsDesigner($person)->willReturn(3);
        $calculator->calculateAsArtist($person)->willReturn(4);
        $calculator->calculateAsPublisher($person)->willReturn(5);

        $person->setProductCountAsDesigner(3)->shouldBeCalled();
        $person->setProductCountAsArtist(4)->shouldBeCalled();
        $person->setProductCountAsPublisher(5)->shouldBeCalled();

        $person->setProductCount(12)->shouldBeCalled();

        $this->update($person);
    }
}
