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

    function its_label_is_mutable()
    {
        $this->setLabel('This is an awesome image.');
        $this->getLabel()->shouldReturn('This is an awesome image.');
    }

    function its_link_url_is_mutable()
    {
        $this->setLinkUrl('http://example.com');
        $this->getLinkUrl()->shouldReturn('http://example.com');
    }
}
