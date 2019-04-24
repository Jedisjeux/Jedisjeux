<?php

namespace spec\App\EventSubscriber;

use App\Entity\ProductFile;
use App\Event\ProductFileEvents;
use App\EventSubscriber\NotifyReviewersForNewFileSubscriber;
use App\NotificationManager\ProductFileNotificationManager;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class NotifyReviewersForNewFileSubscriberSpec extends ObjectBehavior
{
    function let(ProductFileNotificationManager $productFileNotificationManager)
    {
        $this->beConstructedWith($productFileNotificationManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NotifyReviewersForNewFileSubscriber::class);
    }

    function it_implements_subscriber_interface()
    {
        $this->shouldImplement(EventSubscriberInterface::class);
    }

    function it_subscribes_to_events()
    {
        $this::getSubscribedEvents()->shouldReturn([
            ProductFileEvents::POST_CREATE => 'notifyReviewers',
        ]);
    }

    function it_throws_an_invalid_argument_exception_when_subject_is_not_a_product_file(
        GenericEvent $event,
        \stdClass $file
    ): void {
            $event->getSubject()->willReturn($file);

            $this->shouldThrow(\InvalidArgumentException::class)->during('notifyReviewers', [$event]);
    }

    function it_notify_reviewers(
        GenericEvent $event,
        ProductFile $file,
        ProductFileNotificationManager $productFileNotificationManager
    ): void {
        $event->getSubject()->willReturn($file);

        $productFileNotificationManager->notifyReviewers($file)->shouldBeCalled();

        $this->notifyReviewers($event);
    }
}
