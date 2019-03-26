<?php

namespace spec\App\Factory;

use App\Entity\ProductBox;
use App\Entity\ProductInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class ProductBoxFactorySpec extends ObjectBehavior
{
    function let(FactoryInterface $factory, CustomerContextInterface $customerContext)
    {
        $this->beConstructedWith($factory, $customerContext);
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
        $productBox->setAuthor(null)->shouldBeCalled();

        $this->createForProduct($product);
    }

    function it_sets_author_with_current_customer(
        FactoryInterface $factory,
        ProductBox $productBox,
        CustomerContextInterface $customerContext,
        CustomerInterface $author
    ): void {
        $factory->createNew()->willReturn($productBox);
        $customerContext->getCustomer()->willReturn($author);

        $productBox->setAuthor($author)->shouldBeCalled();

        $this->createNew()->shouldReturn($productBox);
    }
}
