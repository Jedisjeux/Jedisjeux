<?php

namespace spec\AppBundle\Document;

use AppBundle\Document\ProductDocument;
use PhpSpec\ObjectBehavior;

class ProductDocumentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ProductDocument::class);
    }

    function its_id_is_mutable(): void
    {
        $this->setId(1);
        $this->getId()->shouldReturn(1);
    }

    function its_slug_is_mutable(): void
    {
        $this->setSlug('carolus-magnus');
        $this->getSlug()->shouldReturn('carolus-magnus');
    }
}
