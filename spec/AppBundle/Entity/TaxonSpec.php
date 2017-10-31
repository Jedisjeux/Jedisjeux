<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\Taxon;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Taxonomy\Model\Taxon as BaseTaxon;

class TaxonSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Taxon::class);
    }

    function it_extends_a_taxon_model(): void
    {
        $this->shouldHaveType(BaseTaxon::class);
    }

    function it_has_zero_topic_count_by_default()
    {
        $this->getTopicCount()->shouldReturn(0);
    }

    function its_topic_count_is_mutable()
    {
        $this->setTopicCount(42);
        $this->getTopicCount()->shouldReturn(42);
    }

    function it_has_zero_product_count_by_default()
    {
        $this->getProductCount()->shouldReturn(0);
    }

    function its_product_count_is_mutable()
    {
        $this->setProductCount(666);
        $this->getProductCount()->shouldReturn(666);
    }

    function it_is_public_by_default()
    {
        $this->isPublic()->shouldReturn(true);
    }
}
