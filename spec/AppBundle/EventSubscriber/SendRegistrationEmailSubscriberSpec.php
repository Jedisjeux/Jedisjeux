<?php

/*
 * This file is part of Monofony.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\AppBundle\EventSubscriber;

use AppBundle\Emails;
use AppBundle\EventSubscriber\SendRegistrationEmailSubscriber;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SendRegistrationEmailSubscriberSpec extends ObjectBehavior
{
    function let(SenderInterface $sender)
    {
        $this->beConstructedWith($sender);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SendRegistrationEmailSubscriber::class);
    }

    function it_is_subscriber()
    {
        $this->shouldImplement(EventSubscriberInterface::class);
    }

    function it_subscriber_to_event()
    {
        $this::getSubscribedEvents()->shouldReturn([
            'sylius.customer.post_register' => 'onRegister',
        ]);
    }

    function it_sends_a_mail_on_register($sender, GenericEvent $event, CustomerInterface $customer)
    {
        $event->getSubject()->willReturn($customer);

        $customer->getEmail()->willReturn('test@example.com');

        $sender->send(Emails::USER_REGISTRATION, array('test@example.com'), array(
            'customer' => $customer,
        ))->shouldBeCalled();

        $this->onRegister($event);
    }
}
