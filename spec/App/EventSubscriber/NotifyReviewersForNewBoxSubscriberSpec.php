<?php

namespace spec\App\EventSubscriber;

use App\Entity\ProductBox;
use App\Event\ProductBoxEvents;
use App\EventSubscriber\NotifyReviewersForNewBoxSubscriber;
use App\NotificationManager\ProductBoxNotificationManager;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class NotifyReviewersForNewBoxSubscriberSpec extends ObjectBehavior
{
    function let(ProductBoxNotificationManager $productBoxNotificationManager)
    {
        $this->beConstructedWith($productBoxNotificationManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NotifyReviewersForNewBoxSubscriber::class);
    }

    function it_implements_subscriber_interface()
    {
        $this->shouldImplement(EventSubscriberInterface::class);
    }

    function it_subscribes_to_events()
    {
        $this::getSubscribedEvents()->shouldReturn([
            ProductBoxEvents::POST_CREATE => 'notifyReviewers',
        ]);
    }

    function it_throws_an_invalid_argument_exception_when_subject_is_not_a_product_box(
        GenericEvent $event,
        \stdClass $box
    ): void {
            $event->getSubject()->willReturn($box);

            $this->shouldThrow(\InvalidArgumentException::class)->during('notifyReviewers', [$event]);
    }

    function it_notify_reviewers(
        GenericEvent $event,
        ProductBox $box,
        ProductBoxNotificationManager $productBoxNotificationManager
    ): void {
        $event->getSubject()->willReturn($box);

        $productBoxNotificationManager->notifyReviewers($box)->shouldBeCalled();

        $this->notifyReviewers($event);
    }
}
