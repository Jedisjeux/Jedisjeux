<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\StaffList;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class StaffListSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(StaffList::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_sets_name()
    {
        $this->setName('Awesome name');

        $this->getName()->shouldReturn('Awesome name');
    }

    function it_sets_description()
    {
        $this->setName('What an awesome description');

        $this->getName()->shouldReturn('What an awesome description');
    }

    function it_sets_start_at(\DateTime $startAt)
    {
        $this->setStartAt($startAt);

        $this->getStartAt()->shouldReturn($startAt);
    }

    function it_sets_end_at(\DateTime $endAt)
    {
        $this->setEndAt($endAt);

        $this->getEndAt()->shouldReturn($endAt);
    }

    function its_products_is_collection()
    {
        $this->getProducts()->shouldHaveType(ArrayCollection::class);
    }

    function it_can_add_products(ProductInterface $product)
    {
        $this->addProduct($product);
        $this->hasProduct($product)->shouldReturn(true);
    }

    function it_can_remove_products(ProductInterface $product)
    {
        $this->addProduct($product);
        $this->removeProduct($product);
        $this->hasProduct($product)->shouldReturn(false);
    }
}
