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

    function it_has_no_first_name_by_default(): void
    {
        $this->getFirstName()->shouldReturn(null);
    }

    function its_first_name_is_mutable(): void
    {
        $this->setFirstName('John');

        $this->getFirstName()->shouldReturn('John');
    }

    function it_has_no_last_name_by_default(): void
    {
        $this->getLastName()->shouldReturn(null);
    }

    function its_last_name_is_mutable(): void
    {
        $this->setLastName('Nathan');

        $this->getLastName()->shouldReturn('Nathan');
    }

    function it_has_no_full_name_by_default(): void
    {
        $this->getFullName()->shouldReturn('');
    }

    function it_can_get_full_name(): void
    {
        $this->setFirstName('Marty');
        $this->setLastName('McFly');

        $this->getFullName()->shouldReturn('Marty McFly');
    }

    function it_has_no_email_by_default(): void
    {
        $this->getEmail()->shouldReturn(null);
    }

    function its_email_is_mutable(): void
    {
        $this->setEmail('john.nathan@example.com');

        $this->getEmail()->shouldReturn('john.nathan@example.com');
    }

    function it_has_no_body_by_default(): void
    {
        $this->getBody()->shouldReturn(null);
    }

    function its_body_is_mutable(): void
    {
        $this->setBody('<p>Contact Request body</p>');

        $this->getBody()->shouldReturn('<p>Contact Request body</p>');
    }
}
