<?php

namespace spec\App\Entity;

use App\Entity\Avatar;
use App\Entity\Customer;
use App\Entity\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Customer\Model\Customer as BaseCustomer;

class CustomerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Customer::class);
    }

    function it_extends_base_customer()
    {
        $this->shouldBeAnInstanceOf(BaseCustomer::class);
    }

    function its_code_is_mutable()
    {
        $this->setCode("CUSTOMER1");
        $this->getCode()->shouldReturn("CUSTOMER1");
    }

    function its_avatar_is_mutable(Avatar $avatar)
    {
        $this->setAvatar($avatar);
        $this->getAvatar()->shouldReturn($avatar);
    }

    function its_user_is_mutable(User $user)
    {
        $this->setUser($user);
        $this->getUser()->shouldReturn($user);
    }
}
