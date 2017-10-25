<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\AbstractImage;
use AppBundle\Entity\GamePlayImage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GamePlayImageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(GamePlayImage::class);
    }

    function it_extends_abstract_image()
    {
        $this->shouldBeAnInstanceOf(AbstractImage::class);
    }
}
