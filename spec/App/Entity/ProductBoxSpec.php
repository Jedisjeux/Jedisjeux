<?php

namespace spec\App\Entity;

use App\Entity\ProductBox;
use App\Entity\ProductBoxImage;
use App\Entity\ProductInterface;
use App\Entity\ProductVariantInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\ResourceInterface;

class ProductBoxSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ProductBox::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_is_new_by_default()
    {
        $this->getStatus()->shouldReturn(ProductBox::STATUS_NEW);
    }

    function it_can_be_accepted()
    {
        $this->setStatus(ProductBox::STATUS_ACCEPTED);
        $this->getStatus()->shouldReturn(ProductBox::STATUS_ACCEPTED);
    }

    function it_can_be_rejected()
    {
        $this->setStatus(ProductBox::STATUS_REJECTED);
        $this->getStatus()->shouldReturn(ProductBox::STATUS_REJECTED);
    }

    function it_has_no_height_by_default()
    {
        $this->getHeight()->shouldReturn(null);
    }

    function its_height_is_mutable()
    {
        $this->setHeight(42);
        $this->getHeight()->shouldReturn(42);
    }

    function it_has_no_real_height_by_default()
    {
        $this->getRealHeight()->shouldReturn(null);
    }

    function its_real_height_is_mutable()
    {
        $this->setRealHeight(420);
        $this->getRealHeight()->shouldReturn(420);

        $this->getHeight()->shouldReturn(271);
    }

    function it_has_no_image_by_default()
    {
        $this->getImage()->shouldReturn(null);
    }

    function its_image_is_mutable(ProductBoxImage $image)
    {
        $this->setImage($image);
        $this->getImage()->shouldReturn($image);
    }

    function it_has_no_product_by_default()
    {
        $this->getProduct()->shouldReturn(null);
    }

    function its_product_is_mutable(ProductInterface $product, ProductVariantInterface $variant)
    {
        $product->getFirstVariant()->willReturn($variant);

        $this->setProduct($product);
        $this->getProduct()->shouldReturn($product);
        $this->getProductVariant()->shouldReturn($variant);
    }

    function it_resets_previous_variant_when_resetting_product(ProductVariantInterface $variant): void
    {
        $this->setProductVariant($variant);

        $this->setProduct(null);

        $this->getProductVariant()->shouldReturn(null);
    }

    function it_has_no_product_variant_by_default()
    {
        $this->getProductVariant()->shouldReturn(null);
    }

    function its_product_variant_is_mutable(ProductVariantInterface $productVariant)
    {
        $productVariant->setBox($this)->shouldBeCalled();

        $this->setProductVariant($productVariant);
        $this->getProductVariant()->shouldReturn($productVariant);
    }

    function it_resets_box_of_previous_variant(ProductVariantInterface $previousVariant, ProductVariantInterface $variant): void
    {
        $this->setProductVariant($previousVariant);

        $previousVariant->setBox(null)->shouldBeCalled();

        $this->setProductVariant($variant);
    }

    function it_does_not_replace_product_variant_if_it_is_already_set(ProductVariantInterface $productVariant): void
    {
        $productVariant->setBox($this)->shouldBeCalledOnce();

        $this->setProductVariant($productVariant);
        $this->setProductVariant($productVariant);
    }
}
