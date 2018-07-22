<?php

namespace spec\AppBundle\Document;

use AppBundle\Document\ImageDocument;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ImageDocumentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ImageDocument::class);
    }

    function its_path_is_mutable()
    {
        $this->setPath("/tmp/image.png");
        $this->getPath()->shouldReturn("/tmp/image.png");
    }
}
