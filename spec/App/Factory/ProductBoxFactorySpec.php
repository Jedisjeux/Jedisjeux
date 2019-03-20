<?php

namespace spec\App\Factory;

use App\Entity\ProductBox;
use App\Factory\ProductBoxFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class ProductBoxFactorySpec extends ObjectBehavior
{
    function let(FactoryInterface $factory)
    {
        $this->beConstructedWith($factory);
    }

    function it_implements_factory_interface(): void
    {
        $this->shouldImplement(FactoryInterface::class);
    }

    function it_creates_product_boxes(FactoryInterface $factory, ProductBox $productBox): void
    {
        $factory->createNew()->willReturn($productBox);

        $this->createNew()->shouldReturn($productBox);
    }

    function it_creates_product_boxes_for_a_product(
        FactoryInterface $factory,
        ProductBox $productBox,
        ProductInterface $product
    ): void {
        $factory->createNew()->willReturn($productBox);

        $productBox->setProduct($product)->shouldBeCalled();

        $this->createForProduct($product);
    }
}
