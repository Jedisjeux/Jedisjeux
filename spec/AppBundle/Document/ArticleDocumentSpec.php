<?php

namespace spec\AppBundle\Document;

use AppBundle\Document\ArticleDocument;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArticleDocumentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ArticleDocument::class);
    }

    function its_id_is_mutable(): void
    {
        $this->setId(1);
        $this->getId()->shouldReturn(1);
    }
}
