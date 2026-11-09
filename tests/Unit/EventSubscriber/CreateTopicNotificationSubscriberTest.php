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
use App\Entity\Notification;
use App\Entity\Post;
use App\Entity\Topic;
use App\EventSubscriber\CreateTopicNotificationSubscriber;
use App\Factory\NotificationFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

final class CreateTopicNotificationSubscriberTest extends TestCase
{
    private CustomerContextInterface&Stub $customerContext;

    private NotificationFactory&MockObject $factory;

    private EntityRepository&Stub $repository;

    private CustomerInterface&Stub $currentCustomer;

    private CreateTopicNotificationSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->currentCustomer = $this->createStub(CustomerInterface::class);
        $this->customerContext = $this->createStub(CustomerContextInterface::class);
        $this->customerContext->method('getCustomer')->willReturn($this->currentCustomer);
        $this->factory = $this->createMock(NotificationFactory::class);
        $this->repository = $this->createStub(EntityRepository::class);

        $translator = $this->createStub(TranslatorInterface::class);
        $translator->method('trans')->willReturn('message');

        $this->subscriber = new CreateTopicNotificationSubscriber(
            $this->customerContext,
            $this->factory,
            $this->createStub(ObjectManager::class),
            $this->repository,
            $translator,
        );
    }

    public function testItSubscribesToPostCreateEvent(): void
    {
        $this->assertSame([
            AppEvents::POST_POST_CREATE => 'onPostCreate',
        ], CreateTopicNotificationSubscriber::getSubscribedEvents());
    }

    public function testItCreatesNotificationsForAllSubjectFollowers(): void
    {
        $follower = $this->createStub(CustomerInterface::class);
        $post = $this->createPost([$follower]);
        $notification = $this->createNotificationMock();

        $this->repository->method('findOneBy')->willReturn(null);
        $this->factory->expects($this->once())->method('createForPost')->with($post, $follower)->willReturn($notification);

        $this->subscriber->onPostCreate(new GenericEvent($post));
    }

    public function testItDoesNotCreateNotificationForCurrentCustomer(): void
    {
        $follower = $this->createStub(CustomerInterface::class);
        $post = $this->createPost([$follower, $this->currentCustomer]);
        $notification = $this->createNotificationMock();

        $this->repository->method('findOneBy')->willReturn(null);
        $this->factory->expects($this->once())->method('createForPost')->with($post, $follower)->willReturn($notification);

        $this->subscriber->onPostCreate(new GenericEvent($post));
    }

    public function testItDoesNotCreateNotificationsWhenPostHasNoTopic(): void
    {
        $post = $this->createStub(Post::class);
        $post->method('getTopic')->willReturn(null);

        $this->factory->expects($this->never())->method('createForPost');

        $this->subscriber->onPostCreate(new GenericEvent($post));
    }

    public function testItUpdatesNotificationsWhenItAlreadyExists(): void
    {
        $follower = $this->createStub(CustomerInterface::class);
        $post = $this->createPost([$follower]);
        $notification = $this->createNotificationMock();

        $this->repository->method('findOneBy')->willReturn($notification);
        $this->factory->expects($this->never())->method('createForPost');

        $this->subscriber->onPostCreate(new GenericEvent($post));
    }

    /**
     * @param list<CustomerInterface> $followers
     */
    private function createPost(array $followers): Post&Stub
    {
        $topic = $this->createStub(Topic::class);
        $topic->method('getTitle')->willReturn('Topic title');
        $topic->method('getFollowers')->willReturn(new ArrayCollection($followers));

        $post = $this->createStub(Post::class);
        $post->method('getTopic')->willReturn($topic);
        $post->method('getAuthor')->willReturn($this->currentCustomer);

        return $post;
    }

    private function createNotificationMock(): Notification&MockObject
    {
        $notification = $this->createMock(Notification::class);
        $notification->expects($this->once())->method('addAuthor')->with($this->currentCustomer);
        $notification->method('getAuthors')->willReturn(new ArrayCollection([$this->currentCustomer]));
        $notification->expects($this->once())->method('setMessage')->with('message');

        return $notification;
    }
}
