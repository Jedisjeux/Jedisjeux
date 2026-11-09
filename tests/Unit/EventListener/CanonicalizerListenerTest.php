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

namespace App\Tests\Unit\EventListener;

use App\EventListener\CanonicalizerListener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\User\Canonicalizer\CanonicalizerInterface;
use Sylius\Component\User\Model\UserInterface;

final class CanonicalizerListenerTest extends TestCase
{
    private CanonicalizerInterface&MockObject $canonicalizer;

    private CanonicalizerListener $listener;

    protected function setUp(): void
    {
        $this->canonicalizer = $this->createMock(CanonicalizerInterface::class);
        $this->listener = new CanonicalizerListener($this->canonicalizer);
    }

    public function testItCanonicalizesUserUsernameOnPrePersistDoctrineEvent(): void
    {
        $user = $this->expectUserCanonicalization();

        $this->listener->prePersist($this->createEvent($user));
    }

    public function testItCanonicalizesCustomerEmailOnPrePersistDoctrineEvent(): void
    {
        $customer = $this->expectCustomerCanonicalization();

        $this->listener->prePersist($this->createEvent($customer));
    }

    public function testItCanonicalizesUserUsernameOnPreUpdateDoctrineEvent(): void
    {
        $user = $this->expectUserCanonicalization();

        $this->listener->preUpdate($this->createEvent($user));
    }

    public function testItCanonicalizesCustomerEmailOnPreUpdateDoctrineEvent(): void
    {
        $customer = $this->expectCustomerCanonicalization();

        $this->listener->preUpdate($this->createEvent($customer));
    }

    public function testItCanonicalizesOnlyUserOrCustomerInterfaceImplementationOnPrePersist(): void
    {
        $this->canonicalizer->expects($this->never())->method('canonicalize');

        $this->listener->prePersist($this->createEvent(new \stdClass()));
    }

    public function testItCanonicalizesOnlyUserOrCustomerInterfaceImplementationOnPreUpdate(): void
    {
        $this->canonicalizer->expects($this->never())->method('canonicalize');

        $this->listener->preUpdate($this->createEvent(new \stdClass()));
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
