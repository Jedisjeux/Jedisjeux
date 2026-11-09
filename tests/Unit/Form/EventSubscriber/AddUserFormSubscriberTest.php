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

use App\Form\EventSubscriber\AddUserFormSubscriber;
use App\Form\Type\User\AppUserType;
use PHPUnit\Framework\TestCase;
use Sylius\Component\User\Model\UserAwareInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;

final class AddUserFormSubscriberTest extends TestCase
{
    private AddUserFormSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->subscriber = new AddUserFormSubscriber();
    }

    public function testItIsEventSubscriberInstance(): void
    {
        $this->assertInstanceOf(EventSubscriberInterface::class, $this->subscriber);
    }

    public function testItAddsUserFormTypeAndCreateUserCheck(): void
    {
        $form = $this->createMock(Form::class);

        $added = [];
        $form->expects($this->exactly(2))->method('add')->willReturnCallback(
            function (string $name, ?string $type, array $options) use (&$added, $form): Form {
                $added[] = [$name, $type];

                return $form;
            },
        );

        $this->subscriber->preSetData(new FormEvent($form, null));

        $this->assertCount(2, $added);
        $this->assertSame(['user', AppUserType::class], $added[0]);
        $this->assertSame('createUser', $added[1][0]);
        $this->assertIsString($added[1][1]);
    }

    public function testItReplacesUserFormByNewUserFormWhenCreateUserCheckIsNotChecked(): void
    {
        $user = $this->createStub(UserInterface::class);
        $user->method('getId')->willReturn(null);
        $customer = $this->createMock(UserAwareInterface::class);
        $customer->method('getUser')->willReturn($user);
        $form = $this->createMock(Form::class);
        $form->method('get')->with('createUser')->willReturn($this->createCreateUserCheckForm(null));
        $event = new FormEvent($form, $customer);

        $customer->expects($this->once())->method('setUser')->with(null);
        $form->expects($this->once())->method('remove')->with('user')->willReturnSelf();
        $form->expects($this->once())->method('add')
            ->with('user', AppUserType::class, $this->isArray())
            ->willReturnSelf();

        $this->subscriber->submit($event);

        $this->assertSame($customer, $event->getData());
    }

    public function testItDoesNotReplaceUserFormByNewUserFormWhenCreateUserCheckIsChecked(): void
    {
        $user = $this->createStub(UserInterface::class);
        $user->method('getId')->willReturn(null);
        $customer = $this->createMock(UserAwareInterface::class);
        $customer->method('getUser')->willReturn($user);
        $form = $this->createMock(Form::class);
        $form->method('get')->with('createUser')->willReturn($this->createCreateUserCheckForm('1'));

        $customer->expects($this->never())->method('setUser');
        $form->expects($this->never())->method('remove');
        $form->expects($this->never())->method('add');

        $this->subscriber->submit(new FormEvent($form, $customer));
    }

    public function testItDoesNotReplaceUserFormByNewUserFormWhenUserHasAnId(): void
    {
        $user = $this->createStub(UserInterface::class);
        $user->method('getId')->willReturn(1);
        $customer = $this->createMock(UserAwareInterface::class);
        $customer->method('getUser')->willReturn($user);
        $form = $this->createMock(Form::class);
        $form->method('get')->with('createUser')->willReturn($this->createCreateUserCheckForm(null));

        $customer->expects($this->never())->method('setUser');
        $form->expects($this->never())->method('remove');
        $form->expects($this->never())->method('add');

        $this->subscriber->submit(new FormEvent($form, $customer));
    }

    public function testItThrowsInvalidArgumentExceptionWhenDataDoesNotImplementUserAwareInterface(): void
    {
        $form = $this->createStub(Form::class);
        $form->method('get')->with('createUser')->willReturn($this->createCreateUserCheckForm(null));

        $this->expectException(\InvalidArgumentException::class);

        $this->subscriber->submit(new FormEvent($form, $this->createStub(UserInterface::class)));
    }

    private function createCreateUserCheckForm(?string $viewData): Form
    {
        $createUserCheckForm = $this->createStub(Form::class);
        $createUserCheckForm->method('getViewData')->willReturn($viewData);

        return $createUserCheckForm;
    }
}
