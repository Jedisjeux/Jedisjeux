<?php

namespace spec\App\Factory;

use App\Context\CustomerContext;
use App\Entity\ContactRequest;
use App\Factory\ContactRequestFactory;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Model\CustomerInterface;

class ContactRequestFactorySpec extends ObjectBehavior
{
    function let(CustomerContext $customerContext)
    {
        $this->beConstructedWith(ContactRequest::class, $customerContext);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContactRequestFactory::class);
    }

    function it_sets_email_with_logged_in_customer(CustomerContext $customerContext, CustomerInterface $customer)
    {
        $customer->getEmail()->willReturn('d.vader@example.com');
        $customerContext->getCustomer()->willReturn($customer);

        $contactRequest = $this->createNew();
        $contactRequest->getEmail()->shouldReturn('d.vader@example.com');
    }
}
