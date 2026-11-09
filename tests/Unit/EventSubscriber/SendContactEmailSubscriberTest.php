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

use App\Emails;
use App\Entity\ContactRequest;
use App\Event\ContactRequestEvents;
use App\EventSubscriber\SendContactEmailSubscriber;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

final class SendContactEmailSubscriberTest extends TestCase
{
    private SenderInterface&MockObject $sender;

    private SendContactEmailSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->sender = $this->createMock(SenderInterface::class);
        $this->subscriber = new SendContactEmailSubscriber($this->sender, 'contact@example.com');
    }

    public function testItSubscribesToPostCreateEvent(): void
    {
        $this->assertSame([
            ContactRequestEvents::POST_CREATE => 'onPostCreate',
        ], SendContactEmailSubscriber::getSubscribedEvents());
    }

    public function testItSentAnEmailOnPostCreate(): void
    {
        $contactRequest = $this->createStub(ContactRequest::class);
        $contactRequest->method('getEmail')->willReturn('recipient@example.com');

        $this->sender->expects($this->once())->method('send')->with(
            Emails::CONTACT_REQUEST,
            ['contact@example.com'],
            ['contact_request' => $contactRequest],
            [],
            ['recipient@example.com'],
        );

        $this->subscriber->onPostCreate(new GenericEvent($contactRequest));
    }

    public function testItThrowsAnInvalidArgumentExceptionWhenEventSubjectIsNotContactRequestType(): void
    {
        $this->sender->expects($this->never())->method('send');

        $this->expectException(\InvalidArgumentException::class);

        $this->subscriber->onPostCreate(new GenericEvent(new \stdClass()));
    }
}
