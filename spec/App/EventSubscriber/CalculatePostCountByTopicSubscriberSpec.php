<?php

namespace spec\App\EventSubscriber;

use App\Entity\Post;
use App\Entity\Topic;
use App\EventSubscriber\CalculatePostCountByTopicSubscriber;
use App\Updater\PostCountByTopicUpdater;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\GenericEvent;

class CalculatePostCountByTopicSubscriberSpec extends ObjectBehavior
{
    function let(PostCountByTopicUpdater $updater)
    {
        $this->beConstructedWith($updater);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CalculatePostCountByTopicSubscriber::class);
    }

    function it_updates_topic(
        GenericEvent $event,
        PostCountByTopicUpdater $updater,
        Post $post,
        Topic $topic
    ): void {
        $event->getSubject()->willReturn($post);
        $post->getTopic()->willReturn($topic);

        $updater->update($topic)->shouldBeCalled();

        $this->onPostCreate($event);
    }

    function it_does_not_update_when_post_has_no_topic(
        GenericEvent $event,
        PostCountByTopicUpdater $updater,
        Post $post
    ): void {
        $event->getSubject()->willReturn($post);
        $post->getTopic()->willReturn(null);

        $updater->update(Argument::type(Topic::class))->shouldNotBeCalled();

        $this->onPostCreate($event);
    }
}
