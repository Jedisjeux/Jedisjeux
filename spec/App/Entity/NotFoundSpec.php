<?php

namespace spec\App\Entity;

use App\Entity\NotFound;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Model\ResourceInterface;
use Zenstruck\RedirectBundle\Model\NotFound as BaseNotFound;

class NotFoundSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith("", "");
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NotFound::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_extends_base_not_found()
    {
        $this->shouldBeAnInstanceOf(BaseNotFound::class);
    }
}
