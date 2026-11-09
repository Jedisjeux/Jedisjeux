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
use App\EventSubscriber\CalculatePostCountByTopicSubscriber;
use App\Updater\PostCountByTopicUpdater;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\GenericEvent;

final class CalculatePostCountByTopicSubscriberTest extends TestCase
{
    private PostCountByTopicUpdater&MockObject $updater;

    private EntityManagerInterface&MockObject $entityManager;

    private CalculatePostCountByTopicSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->updater = $this->createMock(PostCountByTopicUpdater::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->subscriber = new CalculatePostCountByTopicSubscriber($this->updater, $this->entityManager);
    }

    public function testItSubscribesToPostCreateEvent(): void
    {
        $this->assertSame([
            AppEvents::POST_POST_CREATE => 'onPostCreate',
        ], CalculatePostCountByTopicSubscriber::getSubscribedEvents());
    }

    public function testItUpdatesTopic(): void
    {
        $topic = $this->createStub(Topic::class);
        $post = $this->createStub(Post::class);
        $post->method('getTopic')->willReturn($topic);

        $this->updater->expects($this->once())->method('update')->with($topic);
        $this->entityManager->expects($this->once())->method('flush');

        $this->subscriber->onPostCreate(new GenericEvent($post));
    }

    public function testItDoesNotUpdateWhenPostHasNoTopic(): void
    {
        $post = $this->createStub(Post::class);
        $post->method('getTopic')->willReturn(null);

        $this->updater->expects($this->never())->method('update');
        $this->entityManager->expects($this->never())->method('flush');

        $this->subscriber->onPostCreate(new GenericEvent($post));
    }
}
