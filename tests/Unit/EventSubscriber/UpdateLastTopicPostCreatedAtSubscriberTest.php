<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber;

use App\AppEvents;
use App\Entity\Post;
use App\Entity\Topic;
use App\EventSubscriber\UpdateLastTopicPostCreatedAtSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\GenericEvent;

final class UpdateLastTopicPostCreatedAtSubscriberTest extends TestCase
{
    private UpdateLastTopicPostCreatedAtSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->subscriber = new UpdateLastTopicPostCreatedAtSubscriber();
    }

    public function testItSubscribesToEvents(): void
    {
        $this->assertSame([
            AppEvents::TOPIC_PRE_CREATE => 'onTopicCreate',
            AppEvents::POST_PRE_CREATE => 'onPostCreate',
        ], UpdateLastTopicPostCreatedAtSubscriber::getSubscribedEvents());
    }

    public function testItUpdatesLastPostCreatedAtOnTopicCreateEvent(): void
    {
        $topic = $this->createMock(Topic::class);
        $topic->expects($this->once())->method('setLastPostCreatedAt')->with($this->isInstanceOf(\DateTime::class));

        $this->subscriber->onTopicCreate(new GenericEvent($topic));
    }

    public function testItThrowsAnInvalidArgumentExceptionWhenEventSubjectIsNotTopicType(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->subscriber->onTopicCreate(new GenericEvent(new \stdClass()));
    }

    public function testItUpdatesLastPostCreatedAtOnPostCreateEvent(): void
    {
        $topic = $this->createMock(Topic::class);
        $topic->expects($this->once())->method('setLastPostCreatedAt')->with($this->isInstanceOf(\DateTime::class));
        $post = $this->createStub(Post::class);
        $post->method('getTopic')->willReturn($topic);

        $this->subscriber->onPostCreate(new GenericEvent($post));
    }

    public function testItThrowsAnInvalidArgumentExceptionWhenEventSubjectIsNotPostType(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->subscriber->onPostCreate(new GenericEvent(new \stdClass()));
    }

    public function testItDoesNothingWhenPostHasNoTopic(): void
    {
        $post = $this->createStub(Post::class);
        $post->method('getTopic')->willReturn(null);

        $this->expectNotToPerformAssertions();

        $this->subscriber->onPostCreate(new GenericEvent($post));
    }
}
