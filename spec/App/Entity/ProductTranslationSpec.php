<?php

namespace spec\App\Entity;

use App\Entity\ProductTranslation;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Product\Model\ProductTranslation as BaseProductTranslation;

class ProductTranslationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ProductTranslation::class);
    }

    function it_extends_a_product_translation_model(): void
    {
        $this->shouldHaveType(BaseProductTranslation::class);
    }

    function it_has_no_short_description_by_default()
    {
        $this->getShortDescription()->shouldReturn(null);
    }

    function its_short_description_is_mutable()
    {
        $this->setShortDescription("Winter is coming...");
        $this->getShortDescription()->shouldReturn("Winter is coming...");
    }

    function it_has_no_box_content_by_default()
    {
        $this->getBoxContent()->shouldReturn(null);
    }

    function its_box_content_is_mutable()
    {
        $this->setBoxContent("rulebook, meeples");
        $this->getBoxContent()->shouldReturn("rulebook, meeples");
    }
}
