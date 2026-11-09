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

namespace App\Tests\Unit\Form\EventSubscriber;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\EventSubscriber\CustomerRegistrationFormSubscriber;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

final class CustomerRegistrationFormSubscriberTest extends TestCase
{
    private RepositoryInterface&Stub $customerRepository;

    private CustomerRegistrationFormSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->customerRepository = $this->createStub(RepositoryInterface::class);
        $this->subscriber = new CustomerRegistrationFormSubscriber($this->customerRepository);
    }

    public function testItIsEventSubscriberInstance(): void
    {
        $this->assertInstanceOf(EventSubscriberInterface::class, $this->subscriber);
    }

    public function testItListensOnPreSubmitDataEvent(): void
    {
        $this->assertSame([FormEvents::PRE_SUBMIT => 'preSubmit'], $this->subscriber::getSubscribedEvents());
    }

    public function testItSetsUserForExistingCustomer(): void
    {
        $user = $this->createStub(User::class);
        $customer = $this->createStub(Customer::class);
        $customer->method('getUser')->willReturn($user);
        $existingCustomer = $this->createMock(Customer::class);
        $existingCustomer->method('getUser')->willReturn(null);
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($customer);
        $this->customerRepository->method('findOneBy')->with(['email' => 'sylius@example.com'])->willReturn($existingCustomer);

        $existingCustomer->expects($this->once())->method('setUser')->with($user);
        $form->expects($this->once())->method('setData')->with($existingCustomer)->willReturnSelf();

        $this->subscriber->preSubmit(new FormEvent($form, ['email' => 'sylius@example.com']));
    }

    public function testItThrowsUnexpectedTypeExceptionIfDataIsNotCustomerType(): void
    {
        $form = $this->createStub(FormInterface::class);
        $form->method('getData')->willReturn($this->createStub(User::class));

        $this->expectException(\InvalidArgumentException::class);

        $this->subscriber->preSubmit(new FormEvent($form, ['email' => 'sylius@example.com']));
    }

    public function testItDoesNotSetUserIfCustomerWithGivenEmailHasSetUser(): void
    {
        $user = $this->createStub(User::class);
        $existingCustomer = $this->createMock(Customer::class);
        $existingCustomer->method('getUser')->willReturn($user);
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($this->createStub(Customer::class));
        $this->customerRepository->method('findOneBy')->with(['email' => 'sylius@example.com'])->willReturn($existingCustomer);

        $existingCustomer->expects($this->never())->method('setUser');
        $form->expects($this->never())->method('setData');

        $this->subscriber->preSubmit(new FormEvent($form, ['email' => 'sylius@example.com']));
    }
}
