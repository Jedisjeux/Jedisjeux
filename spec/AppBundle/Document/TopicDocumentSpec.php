<?php

namespace spec\AppBundle\Document;

use AppBundle\Document\TopicDocument;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TopicDocumentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TopicDocument::class);
    }

    function its_id_is_mutable(): void
    {
        $this->setId(1);
        $this->getId()->shouldReturn(1);
    }
}
