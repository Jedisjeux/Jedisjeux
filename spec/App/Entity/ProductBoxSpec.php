<?php

namespace spec\App\Entity;

use App\Entity\ProductBox;
use App\Entity\ProductBoxImage;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Product\Model\ProductInterface;
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

    function its_product_is_mutable(ProductInterface $product)
    {
        $this->setProduct($product);
        $this->getProduct()->shouldReturn($product);
    }

    function it_has_no_product_variant_by_default()
    {
        $this->getProductVariant()->shouldReturn(null);
    }

    function its_product_variant_is_mutable(ProductVariantInterface $productVariant)
    {
        $this->setProductVariant($productVariant);
        $this->getProductVariant()->shouldReturn($productVariant);
    }
}
