<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\AbstractImage;
use AppBundle\Entity\Avatar;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AvatarSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Avatar::class);
    }

    function it_extends_abstract_image()
    {
        $this->shouldBeAnInstanceOf(AbstractImage::class);
    }
}
