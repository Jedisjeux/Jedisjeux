<?php

namespace spec\App\Entity;

use App\Entity\ProductList;
use App\Entity\ProductListItem;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class ProductListSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ProductList::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_has_a_generated_code_by_default()
    {
        $this->getCode()->shouldNotBeNull();
    }

    function its_code_is_mutable()
    {
        $this->setCode(ProductList::CODE_GAME_LIBRARY);
        $this->getCode()->shouldReturn(ProductList::CODE_GAME_LIBRARY);
    }

    function it_has_no_name_by_default()
    {
        $this->getName()->shouldReturn(null);
    }

    function its_name_is_mutable()
    {
        $this->setName("My awesome list");
        $this->getName()->shouldReturn("My awesome list");
    }

    function it_has_no_owner_by_default()
    {
        $this->getOwner()->shouldReturn(null);
    }

    function its_owner_is_mutable(CustomerInterface $owner)
    {
        $this->setOwner($owner);
        $this->getOwner()->shouldReturn($owner);
    }

    function it_initializes_items_collection_by_default()
    {
        $this->getItems()->shouldHaveType(Collection::class);
    }

    function it_adds_item(ProductListItem $item)
    {
        $item->setList($this)->shouldBeCalled();

        $this->addItem($item);
        $this->hasItem($item)->shouldReturn(true);
    }

    function it_removes_item(ProductListItem $item)
    {
        $this->addItem($item);
        $this->removeItem($item);
        $this->hasItem($item)->shouldReturn(false);
    }
}
