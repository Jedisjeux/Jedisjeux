<?php

namespace spec\AppBundle\Document;

use AppBundle\Document\PersonDocument;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PersonDocumentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PersonDocument::class);
    }

    function its_id_is_mutable(): void
    {
        $this->setId(1);
        $this->getId()->shouldReturn(1);
    }
}
