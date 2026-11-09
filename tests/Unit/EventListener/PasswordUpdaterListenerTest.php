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

use App\Entity\Customer;
use App\EventListener\PasswordUpdaterListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\User\Security\PasswordUpdaterInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

final class PasswordUpdaterListenerTest extends TestCase
{
    private PasswordUpdaterInterface&MockObject $passwordUpdater;

    private PasswordUpdaterListener $listener;

    protected function setUp(): void
    {
        $this->passwordUpdater = $this->createMock(PasswordUpdaterInterface::class);
        $this->listener = new PasswordUpdaterListener($this->passwordUpdater);
    }

    public function testItUpdatesPasswordForCustomer(): void
    {
        $user = $this->createStub(UserInterface::class);
        $user->method('getPlainPassword')->willReturn('password123');
        $customer = $this->createStub(Customer::class);
        $customer->method('getUser')->willReturn($user);

        $this->passwordUpdater->expects($this->once())->method('updatePassword')->with($user);

        $this->listener->customerUpdateEvent(new GenericEvent($customer));
    }

    public function testItDoesNotUpdatePasswordIfSubjectIsNotInstanceOfCustomerInterface(): void
    {
        $user = $this->createStub(UserInterface::class);

        $this->passwordUpdater->expects($this->never())->method('updatePassword');

        $this->expectException(\InvalidArgumentException::class);

        $this->listener->customerUpdateEvent(new GenericEvent($user));
    }

    public function testItDoesNotUpdatePasswordIfCustomerDoesNotHaveUser(): void
    {
        $customer = $this->createStub(Customer::class);
        $customer->method('getUser')->willReturn(null);

        $this->passwordUpdater->expects($this->never())->method('updatePassword');

        $this->listener->customerUpdateEvent(new GenericEvent($customer));
    }
}
