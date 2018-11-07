<?php

namespace spec\App\EventSubscriber;

use App\Emails;
use App\Entity\ContactRequest;
use App\Event\ContactRequestEvents;
use App\EventSubscriber\SendContactEmailSubscriber;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class SendContactEmailSubscriberSpec extends ObjectBehavior
{
    function let(SenderInterface $sender)
    {
        $this->beConstructedWith($sender, 'contact@example.com');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SendContactEmailSubscriber::class);
    }

    function it_subscribes_to_post_create_event(): void
    {
        $this::getSubscribedEvents()->shouldReturn([
            ContactRequestEvents::POST_CREATE => 'onPostCreate',
        ]);
    }

    function it_sent_an_email_on_post_create(
        GenericEvent $event,
        ContactRequest $contactRequest,
        SenderInterface $sender
    ) {
        $event->getSubject()->willReturn($contactRequest);
        $contactRequest->getEmail()->willReturn('recipient@example.com');

        $sender->send(Emails::CONTACT_REQUEST,
            ['contact@example.com'],
            ['contact_request' => $contactRequest],
            [],
            ['recipient@example.com']
        )->shouldBeCalled();

        $this->onPostCreate($event);
    }

    function it_throws_an_invalid_argument_exception_when_event_subject_is_not_contact_request_type(
        GenericEvent $event,
        \stdClass $contactRequest
    ) {
        $event->getSubject()->willReturn($contactRequest);

        $this->shouldThrow(\InvalidArgumentException::class)->during('onPostCreate', [$event]);
    }
}
