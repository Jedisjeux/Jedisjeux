<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\StaffList;
use AppBundle\Entity\StaffListItem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class StaffListItemSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(StaffListItem::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function its_list_is_mutable(StaffList $list)
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
