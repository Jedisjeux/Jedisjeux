<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\Dealer;
use AppBundle\Entity\DealerImage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Model\ResourceInterface;

class DealerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Dealer::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function its_code_is_mutable()
    {
        $this->setCode("DEALER1");
        $this->getCode()->shouldReturn("DEALER1");
    }

    function it_has_no_name_by_default()
    {
        $this->getName()->shouldReturn(null);
    }

    function its_name_is_mutable()
    {
        $this->setName("Philibert");
        $this->getName()->shouldReturn("Philibert");
    }

    function it_has_no_image_by_default()
    {
        $this->getImage()->shouldReturn(null);
    }

    function its_image_is_mutable(DealerImage $image)
    {
        $this->setImage($image);
        $this->getImage()->shouldReturn($image);
    }
}
