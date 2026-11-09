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

use App\EventSubscriber\CanonicalizerSubscriber;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\User\Canonicalizer\CanonicalizerInterface;
use Sylius\Component\User\Model\UserInterface;

final class CanonicalizerSubscriberTest extends TestCase
{
    private CanonicalizerInterface&MockObject $canonicalizer;

    private CanonicalizerSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->canonicalizer = $this->createMock(CanonicalizerInterface::class);
        $this->subscriber = new CanonicalizerSubscriber($this->canonicalizer);
    }

    public function testItIsASubscriber(): void
    {
        $this->assertInstanceOf(EventSubscriber::class, $this->subscriber);
    }

    public function testItSubscribesToEvents(): void
    {
        $this->assertSame([
            Events::prePersist,
            Events::preUpdate,
        ], $this->subscriber->getSubscribedEvents());
    }

    public function testItCanonicalizesUserUsername(): void
    {
        $user = $this->expectUserCanonicalization();

        $this->subscriber->canonicalize($this->createEvent($user));
    }

    public function testItCanonicalizesUserUsernameOnPrePersistDoctrineEvent(): void
    {
        $user = $this->expectUserCanonicalization();

        $this->subscriber->prePersist($this->createEvent($user));
    }

    public function testItCanonicalizesCustomerEmailOnPrePersistDoctrineEvent(): void
    {
        $customer = $this->expectCustomerCanonicalization();

        $this->subscriber->prePersist($this->createEvent($customer));
    }

    public function testItCanonicalizesUserUsernameOnPreUpdateDoctrineEvent(): void
    {
        $user = $this->expectUserCanonicalization();

        $this->subscriber->preUpdate($this->createEvent($user));
    }

    public function testItCanonicalizesCustomerEmailOnPreUpdateDoctrineEvent(): void
    {
        $customer = $this->expectCustomerCanonicalization();

        $this->subscriber->preUpdate($this->createEvent($customer));
    }

    public function testItCanonicalizesOnlyUserOrCustomerInterfaceImplementationOnPrePersist(): void
    {
        $this->canonicalizer->expects($this->never())->method('canonicalize');

        $this->subscriber->prePersist($this->createEvent(new \stdClass()));
    }

    public function testItCanonicalizesOnlyUserOrCustomerInterfaceImplementationOnPreUpdate(): void
    {
        $this->canonicalizer->expects($this->never())->method('canonicalize');

        $this->subscriber->preUpdate($this->createEvent(new \stdClass()));
    }

    private function expectUserCanonicalization(): UserInterface&MockObject
    {
        $user = $this->createMock(UserInterface::class);
        $user->method('getUsername')->willReturn('testUser');
        $user->method('getEmail')->willReturn('test@email.com');

        $user->expects($this->once())->method('setUsernameCanonical')->with('testuser');
        $user->expects($this->once())->method('setEmailCanonical')->with('test@email.com');
        $this->canonicalizer->expects($this->exactly(2))->method('canonicalize')->willReturnMap([
            ['testUser', 'testuser'],
            ['test@email.com', 'test@email.com'],
        ]);

        return $user;
    }

    private function expectCustomerCanonicalization(): CustomerInterface&MockObject
    {
        $customer = $this->createMock(CustomerInterface::class);
        $customer->method('getEmail')->willReturn('testUser@Email.com');

        $customer->expects($this->once())->method('setEmailCanonical')->with('testuser@email.com');
        $this->canonicalizer->expects($this->once())->method('canonicalize')->with('testUser@Email.com')->willReturn('testuser@email.com');

        return $customer;
    }

    private function createEvent(object $entity): LifecycleEventArgs
    {
        $event = $this->createStub(LifecycleEventArgs::class);
        $event->method('getEntity')->willReturn($entity);

        return $event;
    }
}
