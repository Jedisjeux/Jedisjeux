<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\FestivalList;
use AppBundle\Entity\FestivalListItem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class FestivalListItemSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(FestivalListItem::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function its_list_is_mutable(FestivalList $list)
    {
        $this->setList($list);
        $this->getList()->shouldReturn($list);
    }

    function its_product_is_mutable(ProductInterface $product)
    {
        $this->setProduct($product);
        $this->getProduct()->shouldReturn($product);
    }
}
