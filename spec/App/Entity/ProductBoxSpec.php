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
    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductBox::class);
    }

    function it_implements_resource_interface(): void
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_is_new_by_default(): void
    {
        $this->getStatus()->shouldReturn(ProductBox::STATUS_NEW);
    }

    function it_can_be_accepted(): void
    {
        $this->setStatus(ProductBox::STATUS_ACCEPTED);
        $this->getStatus()->shouldReturn(ProductBox::STATUS_ACCEPTED);
    }

    function it_can_be_rejected(): void
    {
        $this->setStatus(ProductBox::STATUS_REJECTED);
        $this->getStatus()->shouldReturn(ProductBox::STATUS_REJECTED);
    }

    function it_is_not_enabled_by_default(): void
    {
        $this->shouldNotBeEnabled();
    }

    function it_is_toggleable(): void
    {
        $this->enable();
        $this->shouldBeEnabled();

        $this->disable();
        $this->shouldNotBeEnabled();
    }

    function it_has_no_height_by_default(): void
    {
        $this->getHeight()->shouldReturn(null);
    }

    function its_height_is_mutable(): void
    {
        $this->setHeight(42);
        $this->getHeight()->shouldReturn(42);
    }

    function it_has_no_real_height_by_default(): void
    {
        $this->getRealHeight()->shouldReturn(null);
    }

    function its_real_height_is_mutable(): void
    {
        $this->setRealHeight(420);
        $this->getRealHeight()->shouldReturn(420);

        $this->getHeight()->shouldReturn(271);
    }

    function it_has_no_image_by_default(): void
    {
        $this->getImage()->shouldReturn(null);
    }

    function its_image_is_mutable(ProductBoxImage $image): void
    {
        $this->setImage($image);
        $this->getImage()->shouldReturn($image);
    }

    function it_has_no_product_by_default(): void
    {
        $this->getProduct()->shouldReturn(null);
    }

    function its_product_is_mutable(ProductInterface $product, ProductVariantInterface $variant): void
    {
        $product->getFirstVariant()->willReturn($variant);

        $this->setProduct($product);
        $this->getProduct()->shouldReturn($product);
        $this->getProductVariant()->shouldReturn($variant);
    }

    function it_has_no_product_variant_by_default(): void
    {
        $this->getProductVariant()->shouldReturn(null);
    }

    function its_product_variant_is_mutable(ProductVariantInterface $productVariant): void
    {
        $this->setProductVariant($productVariant);
        $this->getProductVariant()->shouldReturn($productVariant);
    }
}
