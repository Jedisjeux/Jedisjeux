<?php

namespace spec\App\Factory;

use App\Entity\ProductFile;
use App\Entity\ProductInterface;
use App\Factory\ProductFileFactory;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface as BaseCustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class ProductFileFactorySpec extends ObjectBehavior
{
    function let(FactoryInterface $factory, CustomerContextInterface $customerContext)
    {
        $this->beConstructedWith($factory, $customerContext);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductFileFactory::class);
    }

    function it_implements_factory_interface(): void
    {
        $this->shouldImplement(FactoryInterface::class);
    }

    function it_creates_product_boxes(FactoryInterface $factory, ProductFile $productFile): void
    {
        $factory->createNew()->willReturn($productFile);

        $this->createNew()->shouldReturn($productFile);
    }

    function it_creates_product_boxes_for_a_product(
        FactoryInterface $factory,
        ProductFile $productBox,
        ProductInterface $product
    ): void {
        $factory->createNew()->willReturn($productBox);

        $productBox->setProduct($product)->shouldBeCalled();
        $productBox->setAuthor(null)->shouldBeCalled();

        $this->createForProduct($product);
    }

    function it_throws_an_invalid_argument_exception_when_customer_extends_a_wrong_customer_model(
        CustomerContextInterface $customerContext,
        BaseCustomerInterface $customer
    ): void {
        $customerContext->getCustomer()->willReturn($customer);

        $this->shouldThrow(\InvalidArgumentException::class)->during('createNew');
    }
}
