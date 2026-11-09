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

namespace App\Tests\Unit\Entity;

use App\Entity\AppUserInterface;
use App\Entity\Avatar;
use App\Entity\Customer;
use App\Entity\CustomerInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\Customer as BaseCustomer;
use Sylius\Component\User\Model\UserInterface;

final class CustomerTest extends TestCase
{
    private Customer $sut;

    protected function setUp(): void
    {
        $this->sut = new Customer();
    }

    public function testItImplementsACustomerInterface(): void
    {
        $this->assertInstanceOf(CustomerInterface::class, $this->sut);
    }

    public function testItExtendsBaseCustomerModel(): void
    {
        $this->assertInstanceOf(BaseCustomer::class, $this->sut);
    }

    public function testItHasNoUserByDefault(): void
    {
        $this->assertNull($this->sut->getUser());
    }

    public function testItsUserIsMutable(): void
    {
        $user = $this->createMock(AppUserInterface::class);
        $user->expects($this->once())->method('setCustomer')->with($this->sut);

        $this->sut->setUser($user);
        $this->assertSame($user, $this->sut->getUser());
    }

    public function testItThrowsAnInvalidArgumentExceptionWhenUserIsNotAnAppUserType(): void
    {
        $user = $this->createStub(UserInterface::class);

        $this->expectException(\InvalidArgumentException::class);

        $this->sut->setUser($user);
    }

    public function testItResetsCustomerOfPreviousUser(): void
    {
        $customers = [];
        $previousUser = $this->createMock(AppUserInterface::class);
        $previousUser->expects($this->exactly(2))->method('setCustomer')->willReturnCallback(function ($customer) use (&$customers, $previousUser) {
            $customers[] = $customer;

            return $previousUser;
        });
        $user = $this->createStub(AppUserInterface::class);

        $this->sut->setUser($previousUser);
        $this->sut->setUser($user);

        $this->assertSame([$this->sut, null], $customers);
    }

    public function testItDoesNotReplaceUserIfItIsAlreadySet(): void
    {
        $user = $this->createMock(AppUserInterface::class);
        $user->expects($this->once())->method('setCustomer')->with($this->sut);

        $this->sut->setUser($user);
        $this->sut->setUser($user);
    }

    public function testItsHasNoCodeByDefault(): void
    {
        $this->assertNull($this->sut->getCode());
    }

    public function testItsCodeIsMutable(): void
    {
        $this->sut->setCode("CUSTOMER1");
        $this->assertSame("CUSTOMER1", $this->sut->getCode());
    }

    public function testItsHasNoAvatarByDefault(): void
    {
        $this->assertNull($this->sut->getAvatar());
    }

    public function testItsAvatarIsMutable(): void
    {
        $avatar = $this->createStub(Avatar::class);

        $this->sut->setAvatar($avatar);
        $this->assertSame($avatar, $this->sut->getAvatar());
    }

    public function testItsToStringConversionReturnsCustomerEmailWhenNoUserAssociated(): void
    {
        $this->sut->setEmail('marty.mcfly@future.com');
        $this->assertSame('marty.mcfly@future.com', (string) $this->sut);
    }

    public function testItsToStringConversionReturnsUsernameWhenThereIsAUserAssociated(): void
    {
        $user = $this->createStub(AppUserInterface::class);
        $user->method('getUsername')->willReturn('MartyMcFly2015');

        $this->sut->setUser($user);
        $this->assertSame('MartyMcFly2015', (string) $this->sut);
    }
}
