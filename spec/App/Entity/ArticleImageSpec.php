<?php

namespace spec\App\Entity;

use App\Entity\AbstractImage;
use App\Entity\ArticleImage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArticleImageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ArticleImage::class);
    }

    function it_extends_abstract_image()
    {
        $this->shouldBeAnInstanceOf(AbstractImage::class);
    }
}
