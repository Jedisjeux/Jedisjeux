<?php

namespace spec\App\Factory;

use App\Context\CustomerContext;
use App\Entity\ProductList;
use App\Factory\ProductListFactory;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ProductListFactorySpec extends ObjectBehavior
{
    function let(CustomerContext $customerContext, TranslatorInterface $translator)
    {
        $this->beConstructedWith(ProductList::class, $customerContext, $translator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductListFactory::class);
    }

    function it_sets_owner(CustomerContext $customerContext, CustomerInterface $customer)
    {
        $customerContext->getCustomer()->willReturn($customer);

        $productList = $this->createNew();
        $productList->getOwner()->shouldReturn($customer);
    }

    function it_creates_a_list_for_code(): void
    {
        $productList = $this->createForCode('XYZ');
        $productList->getCode()->shouldReturn('XYZ');
    }
}
