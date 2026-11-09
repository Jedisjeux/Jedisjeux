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

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\User\Model\User as BaseUser;

final class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testItExtendsAUserModel(): void
    {
        $this->assertInstanceOf(BaseUser::class, $this->user);
    }

    public function testItHasNoCustomerByDefault(): void
    {
        $this->assertNull($this->user->getCustomer());
    }

    public function testItsCustomerIsMutable(): void
    {
        $customer = $this->createStub(CustomerInterface::class);

        $this->user->setCustomer($customer);

        $this->assertSame($customer, $this->user->getCustomer());
    }
}
