<?php

namespace spec\App\Entity;

use App\Entity\AbstractImage;
use PhpSpec\ObjectBehavior;

class GameAwardImageSpec extends ObjectBehavior
{
    function it_extends_abstract_image()
    {
        $this->shouldBeAnInstanceOf(AbstractImage::class);
    }
}
