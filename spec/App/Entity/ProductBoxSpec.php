<?php

namespace spec\App\Entity;

use App\Entity\ProductBox;
use App\Entity\ProductBoxImage;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Product\Model\ProductVariantInterface;
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

    function it_has_no_height_by_default()
    {
        $this->getHeight()->shouldReturn(null);
    }

    function its_height_is_mutable()
    {
        $this->setHeight(42.0);
        $this->getHeight()->shouldReturn(42.0);
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
