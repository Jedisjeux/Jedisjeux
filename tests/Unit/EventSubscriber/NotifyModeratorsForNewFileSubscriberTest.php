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

use App\Entity\ProductFile;
use App\Event\ProductFileEvents;
use App\EventSubscriber\NotifyModeratorsForNewFileSubscriber;
use App\NotificationManager\ProductFileNotificationManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

final class NotifyModeratorsForNewFileSubscriberTest extends TestCase
{
    private ProductFileNotificationManager&MockObject $notificationManager;

    private NotifyModeratorsForNewFileSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->notificationManager = $this->createMock(ProductFileNotificationManager::class);
        $this->subscriber = new NotifyModeratorsForNewFileSubscriber($this->notificationManager);
    }

    public function testItImplementsSubscriberInterface(): void
    {
        $this->assertInstanceOf(EventSubscriberInterface::class, $this->subscriber);
    }

    public function testItSubscribesToEvents(): void
    {
        $this->assertSame([
            ProductFileEvents::POST_CREATE => 'notifyModerators',
        ], NotifyModeratorsForNewFileSubscriber::getSubscribedEvents());
    }

    public function testItThrowsAnInvalidArgumentExceptionWhenSubjectIsNotAProductFile(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->subscriber->notifyModerators(new GenericEvent(new \stdClass()));
    }

    public function testItNotifyModerators(): void
    {
        $file = $this->createStub(ProductFile::class);

        $this->notificationManager->expects($this->once())->method('notifyModerators')->with($file);

        $this->subscriber->notifyModerators(new GenericEvent($file));
    }
}
