<?php

namespace spec\App\Entity;

use App\Entity\AbstractImage;
use App\Entity\BlockImage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BlockImageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BlockImage::class);
    }

    function it_extends_abstract_image()
    {
        $this->shouldBeAnInstanceOf(AbstractImage::class);
    }
}
