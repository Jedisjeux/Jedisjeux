<?php

namespace spec\App\Entity;

use App\Entity\AppUserInterface;
use App\Entity\Avatar;
use App\Entity\Customer;
use App\Entity\CustomerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Model\Customer as BaseCustomer;
use Sylius\Component\User\Model\UserInterface;

class CustomerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Customer::class);
    }

    function it_implements_a_customer_interface(): void
    {
        $this->shouldImplement(CustomerInterface::class);
    }

    function it_extends_base_customer_model(): void
    {
        $this->shouldBeAnInstanceOf(BaseCustomer::class);
    }

    function it_has_no_user_by_default(): void
    {
        $this->getUser()->shouldReturn(null);
    }

    function its_user_is_mutable(AppUserInterface $user): void
    {
        $user->setCustomer($this)->shouldBeCalled();

        $this->setUser($user);
        $this->getUser()->shouldReturn($user);
    }

    function it_throws_an_invalid_argument_exception_when_user_is_not_an_app_user_type(UserInterface $user): void
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('setUser', [$user]);
    }

    function it_resets_customer_of_previous_user(AppUserInterface $previousUser, AppUserInterface $user): void
    {
        $this->setUser($previousUser);

        $previousUser->setCustomer(null)->shouldBeCalled();

        $this->setUser($user);
    }

    function it_does_not_replace_user_if_it_is_already_set(AppUserInterface $user): void
    {
        $user->setCustomer($this)->shouldBeCalledOnce();

        $this->setUser($user);
        $this->setUser($user);
    }

    function its_has_no_code_by_default(): void
    {
        $this->getCode()->shouldReturn(null);
    }

    function its_code_is_mutable(): void
    {
        $this->setCode("CUSTOMER1");
        $this->getCode()->shouldReturn("CUSTOMER1");
    }

    function its_has_no_avatar_by_default(): void
    {
        $this->getAvatar()->shouldReturn(null);
    }

    function its_avatar_is_mutable(Avatar $avatar): void
    {
        $this->setAvatar($avatar);
        $this->getAvatar()->shouldReturn($avatar);
    }

    function its_to_string_conversion_returns_customer_email_when_no_user_associated(): void
    {
        $this->setEmail('marty.mcfly@future.com');
        $this::__toString()->shouldReturn('marty.mcfly@future.com');
    }

    function its_to_string_conversion_returns_username_when_there_is_a_user_associated(AppUserInterface $user): void
    {
        $this->setUser($user);
        $user->getUsername()->willReturn('MartyMcFly2015');

        $this::__toString()->shouldReturn('MartyMcFly2015');
    }
}
