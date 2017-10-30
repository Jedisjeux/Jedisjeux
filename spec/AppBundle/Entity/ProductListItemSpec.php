<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\ProductList;
use AppBundle\Entity\ProductListItem;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class ProductListItemSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ProductListItem::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_has_no_list_by_default()
    {
        $this->getList()->shouldReturn(null);
    }

    function its_list_is_mutable(ProductList $list)
    {
        $this->setList($list);
        $this->getList()->shouldReturn($list);
    }

    function it_ha_no_product_by_default()
    {
        $this->getProduct()->shouldReturn(null);
    }

    function its_product_is_mutable(ProductInterface $product)
    {
        $this->setProduct($product);
        $this->getProduct()->shouldReturn($product);
    }
}
