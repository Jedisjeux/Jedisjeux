<?php

namespace spec\App\Calculator;

use App\Calculator\TopicCountByTaxonCalculator;
use App\Repository\TopicRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

class TopicCountByTaxonCalculatorSpec extends ObjectBehavior
{
    function let(TopicRepository $topicRepository)
    {
        $this->beConstructedWith($topicRepository);
    }

    function it_calculates_topic_count_for_a_taxon(
        TopicRepository $topicRepository,
        TaxonInterface $taxon
    ): void {
        $topicRepository->countByTaxon($taxon)->willReturn(3);

        $this->calculate($taxon)->shouldReturn(3);
    }
}
