<?php

namespace spec\App\Updater;

use App\Calculator\ProductCountByTaxonCalculator;
use App\Entity\Taxon;
use PhpSpec\ObjectBehavior;

class ProductCountByTaxonUpdaterSpec extends ObjectBehavior
{
    function let(ProductCountByTaxonCalculator $calculator)
    {
        $this->beConstructedWith($calculator);
    }

    function it_updates_taxon_with_product_count(ProductCountByTaxonCalculator $calculator, Taxon $taxon)
    {
        $calculator->calculate($taxon)->willReturn(5);

        $taxon->setProductCount(5)->shouldBeCalled();

        $this->update($taxon);
    }
}
