<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\AbstractImage;
use AppBundle\Entity\DealerImage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DealerImageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DealerImage::class);
    }

    function it_extends_abstract_image()
    {
        $this->shouldBeAnInstanceOf(AbstractImage::class);
    }
}
