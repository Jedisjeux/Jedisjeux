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
use App\EventListener\UserRegistrationListener;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\UserBundle\UserEvents;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\User\Security\Generator\GeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

final class UserRegistrationListenerTest extends TestCase
{
    private ObjectManager&MockObject $userManager;

    private GeneratorInterface&MockObject $tokenGenerator;

    private EventDispatcherInterface&MockObject $eventDispatcher;

    private UserRegistrationListener $listener;

    protected function setUp(): void
    {
        $this->userManager = $this->createMock(ObjectManager::class);
        $this->tokenGenerator = $this->createMock(GeneratorInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->listener = new UserRegistrationListener(
            $this->userManager,
            $this->tokenGenerator,
            $this->eventDispatcher,
        );
    }

    public function testItSendsAnUserVerificationEmail(): void
    {
        $user = $this->createMock(UserInterface::class);
        $customer = $this->createStub(Customer::class);
        $customer->method('getUser')->willReturn($user);

        $this->tokenGenerator->method('generate')->willReturn('1d7dbc5c3dbebe5c');
        $user->expects($this->once())->method('setEmailVerificationToken')->with('1d7dbc5c3dbebe5c');

        $this->userManager->expects($this->once())->method('persist')->with($user);
        $this->userManager->expects($this->once())->method('flush');

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(GenericEvent::class), UserEvents::REQUEST_VERIFICATION_TOKEN)
            ->willReturnArgument(0)
        ;

        $this->listener->handleUserVerification(new GenericEvent($customer));
    }

    public function testItThrowsAnInvalidArgumentExceptionIfEventSubjectIsNotCustomerType(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->listener->handleUserVerification(new GenericEvent(new \stdClass()));
    }

    public function testItThrowsAnInvalidArgumentExceptionIfUserIsNull(): void
    {
        $customer = $this->createStub(Customer::class);
        $customer->method('getUser')->willReturn(null);

        $this->expectException(\InvalidArgumentException::class);

        $this->listener->handleUserVerification(new GenericEvent($customer));
    }
}
