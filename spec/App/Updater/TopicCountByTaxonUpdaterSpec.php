<?php

namespace spec\App\Updater;

use App\Calculator\TopicCountByTaxonCalculator;
use App\Entity\Taxon;
use PhpSpec\ObjectBehavior;

class TopicCountByTaxonUpdaterSpec extends ObjectBehavior
{
    function let(TopicCountByTaxonCalculator $calculator)
    {
        $this->beConstructedWith($calculator);
    }

    function it_updates_taxon_with_product_count(TopicCountByTaxonCalculator $calculator, Taxon $taxon)
    {
        $calculator->calculate($taxon)->willReturn(5);

        $taxon->setTopicCount(5)->shouldBeCalled();

        $this->update($taxon);
    }
}
