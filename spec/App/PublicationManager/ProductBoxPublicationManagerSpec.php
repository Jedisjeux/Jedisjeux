<?php

namespace spec\App\PublicationManager;

use App\Entity\ProductBox;
use App\Entity\ProductVariantInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;

class ProductBoxPublicationManagerSpec extends ObjectBehavior
{
    function let(ObjectManager $objectManager)
    {
        $this->beConstructedWith($objectManager);
    }

    function it_enables_a_product_box(
        ProductVariantInterface $variant,
        ProductBox $box,
        ObjectManager $objectManager
    ): void {
        $box->getProductVariant()->willReturn($variant);

        $box->enable()->shouldBeCalled();
        $objectManager->flush()->shouldBeCalled();

        $this->enable($box);
    }

    function it_disables_previous_enabled_box(
        ProductVariantInterface $variant,
        ProductBox $previousBox,
        ProductBox $box
    ): void {
        $box->getProductVariant()->willReturn($variant);
        $variant->getEnabledBox()->willReturn($previousBox);

        $previousBox->disable()->shouldBeCalled();
        $box->enable()->shouldBeCalled();

        $this->enable($box);
    }

    function it_throws_an_invalid_argument_exception_if_box_has_no_product_variant(
        ProductBox $box
    ): void {
        $box->getProductVariant()->willReturn(null);

        $this->shouldThrow(\InvalidArgumentException::class)->during('enable', [$box]);
    }
}
