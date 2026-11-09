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
use App\Context\CustomerContext;
use App\Entity\Post;
use App\Entity\Topic;
use App\EventSubscriber\AddTopicFollowerSubscriber;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

final class AddTopicFollowerSubscriberTest extends TestCase
{
    private CustomerContext&Stub $customerContext;

    private CustomerInterface&Stub $customer;

    private AddTopicFollowerSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->customer = $this->createStub(CustomerInterface::class);
        $this->customerContext = $this->createStub(CustomerContext::class);
        $this->customerContext->method('getCustomer')->willReturn($this->customer);
        $this->subscriber = new AddTopicFollowerSubscriber($this->customerContext);
    }

    public function testItIsASubscriber(): void
    {
        $this->assertInstanceOf(EventSubscriberInterface::class, $this->subscriber);
    }

    public function testItSubscribesToEvent(): void
    {
        $this->assertSame([
            AppEvents::TOPIC_PRE_CREATE => 'onTopicCreate',
            AppEvents::POST_PRE_CREATE => 'onPostCreate',
        ], AddTopicFollowerSubscriber::getSubscribedEvents());
    }

    public function testItAddsCurrentlyLoggedCustomerAsFollowerOnTopicCreate(): void
    {
        $topic = $this->createMock(Topic::class);
        $topic->expects($this->once())->method('addFollower')->with($this->customer);

        $this->subscriber->onTopicCreate(new GenericEvent($topic));
    }

    public function testItAddsCurrentlyLoggedCustomerAsFollowerOnPostCreate(): void
    {
        $topic = $this->createMock(Topic::class);
        $topic->expects($this->once())->method('addFollower')->with($this->customer);
        $post = $this->createStub(Post::class);
        $post->method('getTopic')->willReturn($topic);

        $this->subscriber->onPostCreate(new GenericEvent($post));
    }

    public function testItDoesNothingWhenPostHasNoTopic(): void
    {
        $post = $this->createStub(Post::class);
        $post->method('getTopic')->willReturn(null);

        $this->expectNotToPerformAssertions();

        $this->subscriber->onPostCreate(new GenericEvent($post));
    }
}
