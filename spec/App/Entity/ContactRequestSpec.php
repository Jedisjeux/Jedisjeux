<?php

namespace spec\App\Entity;

use App\Entity\ContactRequest;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\ResourceInterface;

class ContactRequestSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ContactRequest::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_sets_first_name()
    {
        $this->setFirstName('John');

        $this->getFirstName()->shouldReturn('John');
    }

    function it_sets_last_name()
    {
        $this->setLastName('Nathan');

        $this->getLastName()->shouldReturn('Nathan');
    }

    function it_sets_email()
    {
        $this->setEmail('john.nathan@example.com');

        $this->getEmail()->shouldReturn('john.nathan@example.com');
    }

    function it_sets_body()
    {
        $this->setBody('<p>Contact Request body</p>');

        $this->getBody()->shouldReturn('<p>Contact Request body</p>');
    }
}
