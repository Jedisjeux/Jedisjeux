<?php

namespace spec\App\Entity;

use App\Entity\Dealer;
use App\Entity\DealerContact;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\ResourceInterface;

class DealerContactSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DealerContact::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function its_email_is_mutable()
    {
        $this->setEmail('jon.snow@example.com');
        $this->getEmail()->shouldReturn('jon.snow@example.com');
    }

    function its_first_name_is_mutable()
    {
        $this->setFirstName('Jon');
        $this->getFirstName()->shouldReturn('Jon');
    }

    function its_last_name_is_mutable()
    {
        $this->setLastName('Snow');
        $this->getLastName()->shouldReturn('Snow');
    }

    function its_dealer_is_mutable(Dealer $dealer)
    {
        $this->setDealer($dealer);
        $this->getDealer()->shouldReturn($dealer);
    }
}
