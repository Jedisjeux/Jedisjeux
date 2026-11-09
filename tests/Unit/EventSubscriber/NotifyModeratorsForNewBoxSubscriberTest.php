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

use App\Entity\ProductBox;
use App\Event\ProductBoxEvents;
use App\EventSubscriber\NotifyModeratorsForNewBoxSubscriber;
use App\NotificationManager\ProductBoxNotificationManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

final class NotifyModeratorsForNewBoxSubscriberTest extends TestCase
{
    private ProductBoxNotificationManager&MockObject $notificationManager;

    private NotifyModeratorsForNewBoxSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->notificationManager = $this->createMock(ProductBoxNotificationManager::class);
        $this->subscriber = new NotifyModeratorsForNewBoxSubscriber($this->notificationManager);
    }

    public function testItImplementsSubscriberInterface(): void
    {
        $this->assertInstanceOf(EventSubscriberInterface::class, $this->subscriber);
    }

    public function testItSubscribesToEvents(): void
    {
        $this->assertSame([
            ProductBoxEvents::POST_CREATE => 'notifyModerators',
        ], NotifyModeratorsForNewBoxSubscriber::getSubscribedEvents());
    }

    public function testItThrowsAnInvalidArgumentExceptionWhenSubjectIsNotAProductBox(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->subscriber->notifyModerators(new GenericEvent(new \stdClass()));
    }

    public function testItNotifyModerators(): void
    {
        $box = $this->createStub(ProductBox::class);

        $this->notificationManager->expects($this->once())->method('notifyModerators')->with($box);

        $this->subscriber->notifyModerators(new GenericEvent($box));
    }
}
