<?php

namespace spec\App\EventSubscriber;

use App\AppEvents;
use App\Entity\Post;
use App\Entity\Topic;
use App\EventSubscriber\UpdateLastTopicPostCreatedAtSubscriber;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\GenericEvent;

class UpdateLastTopicPostCreatedAtSubscriberSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(UpdateLastTopicPostCreatedAtSubscriber::class);
    }

    function it_subscribes_to_events()
    {
        $this::getSubscribedEvents()->shouldReturn([
            AppEvents::TOPIC_PRE_CREATE => 'onTopicCreate',
            AppEvents::POST_PRE_CREATE => 'onPostCreate',
        ]);
    }

    function it_updates_last_post_created_at_on_topic_create_event(
        GenericEvent $event,
        Topic $topic
    ) {
        $event->getSubject()->willReturn($topic);

        $topic->setLastPostCreatedAt(Argument::type(\DateTime::class))->shouldBeCalled();

        $this->onTopicCreate($event);
    }

    function it_throws_an_invalid_argument_exception_when_event_subject_is_not_topic_type(
        GenericEvent $event,
        \stdClass $topic
    ) {
        $event->getSubject()->willReturn($topic);

        $this->shouldThrow(\InvalidArgumentException::class)->during('onTopicCreate', [$event]);
    }

    function it_updates_last_post_created_at_on_post_create_event(
        GenericEvent $event,
        Post $post,
        Topic $topic
    ) {
        $event->getSubject()->willReturn($post);
        $post->getTopic()->willReturn($topic);

        $topic->setLastPostCreatedAt(Argument::type(\DateTime::class))->shouldBeCalled();

        $this->onPostCreate($event);
    }

    function it_throws_an_invalid_argument_exception_when_event_subject_is_not_post_type(
        GenericEvent $event,
        \stdClass $post
    ) {
        $event->getSubject()->willReturn($post);

        $this->shouldThrow(\InvalidArgumentException::class)->during('onPostCreate', [$event]);
    }

    function it_does_nothing_when_post_has_no_topic(
        GenericEvent $event,
        Post $post,
        Topic $topic
    ) {
        $event->getSubject()->willReturn($post);
        $post->getTopic()->willReturn(null);

        $topic->setLastPostCreatedAt(Argument::type(\DateTime::class))->shouldNotBeCalled();

        $this->onPostCreate($event);
    }
}
