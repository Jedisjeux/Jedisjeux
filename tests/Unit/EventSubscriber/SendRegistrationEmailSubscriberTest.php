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
use App\EventSubscriber\SendRegistrationEmailSubscriber;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

final class SendRegistrationEmailSubscriberTest extends TestCase
{
    private SenderInterface&MockObject $sender;

    private SendRegistrationEmailSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->sender = $this->createMock(SenderInterface::class);
        $this->subscriber = new SendRegistrationEmailSubscriber($this->sender);
    }

    public function testItIsSubscriber(): void
    {
        $this->assertInstanceOf(EventSubscriberInterface::class, $this->subscriber);
    }

    public function testItSubscribesToEvent(): void
    {
        $this->assertSame([
            'sylius.customer.post_register' => 'onRegister',
        ], SendRegistrationEmailSubscriber::getSubscribedEvents());
    }

    public function testItSendsAMailOnRegister(): void
    {
        $customer = $this->createStub(CustomerInterface::class);
        $customer->method('getEmail')->willReturn('test@example.com');

        $this->sender->expects($this->once())->method('send')->with(
            Emails::USER_REGISTRATION,
            ['test@example.com'],
            ['customer' => $customer],
        );

        $this->subscriber->onRegister(new GenericEvent($customer));
    }
}
