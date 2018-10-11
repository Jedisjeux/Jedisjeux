<?php

namespace spec\App\Entity;

use App\Entity\AbstractImage;
use App\Entity\ProductBoxImage;
use PhpSpec\ObjectBehavior;

class ProductBoxImageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ProductBoxImage::class);
    }

    function it_extends_abstract_image()
    {
        $this->shouldBeAnInstanceOf(AbstractImage::class);
    }
}
