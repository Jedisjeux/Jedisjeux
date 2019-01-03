<?php

namespace spec\App\Entity;

use App\Entity\GameAward;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class YearAwardSpec extends ObjectBehavior
{
    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    function its_has_no_year_by_default()
    {
        $this->getYear()->shouldReturn(null);
    }

    function its_year_is_mutable()
    {
        $this->setYear('2018');
        $this->getYear()->shouldReturn('2018');
    }

    function its_has_no_award_by_default()
    {
        $this->getAward()->shouldReturn(null);
    }

    function its_award_is_mutable(GameAward $award)
    {
        $this->setAward($award);
        $this->getAward()->shouldReturn($award);
    }

    function it_has_no_product_by_default()
    {
        $this->getProduct()->shouldReturn(null);
    }

    function its_product_is_mutable(ProductInterface $product)
    {
        $this->setProduct($product);
        $this->getProduct()->shouldReturn($product);
    }

    function it_has_no_name_by_default()
    {
        $this->getName()->shouldReturn(null);
    }

    function it_can_get_name(GameAward $award)
    {
        $this->setYear('2018');
        $this->setAward($award);
        $award->getName()->willReturn('Spiel des Jahres');

        $this->getName()->shouldReturn('Spiel des Jahres 2018');
    }
}
