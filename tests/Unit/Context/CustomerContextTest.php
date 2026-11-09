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

namespace App\Tests\Unit\Context;

use App\Context\CustomerContext;
use App\Entity\Customer;
use App\Entity\User;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class CustomerContextTest extends TestCase
{
    private TokenStorageInterface&Stub $tokenStorage;

    private AuthorizationCheckerInterface&Stub $authorizationChecker;

    private CustomerContext $customerContext;

    protected function setUp(): void
    {
        $this->tokenStorage = $this->createStub(TokenStorageInterface::class);
        $this->authorizationChecker = $this->createStub(AuthorizationCheckerInterface::class);
        $this->customerContext = new CustomerContext($this->tokenStorage, $this->authorizationChecker);
    }

    public function testItGetsCustomerFromCurrentlyLoggedUser(): void
    {
        $customer = $this->createStub(Customer::class);
        $user = $this->createStub(User::class);
        $user->method('getCustomer')->willReturn($customer);
        $token = $this->createStub(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $this->tokenStorage->method('getToken')->willReturn($token);
        $this->authorizationChecker->method('isGranted')->with('IS_AUTHENTICATED_REMEMBERED')->willReturn(true);

        $this->assertSame($customer, $this->customerContext->getCustomer());
    }

    public function testItReturnsNullIfUserIsNotLoggedIn(): void
    {
        $this->tokenStorage->method('getToken')->willReturn(null);

        $this->assertNull($this->customerContext->getCustomer());
    }

    public function testItReturnsNullIfUserIsNotAnUserInstance(): void
    {
        $token = $this->createStub(TokenInterface::class);
        $token->method('getUser')->willReturn($this->createStub(UserInterface::class));

        $this->tokenStorage->method('getToken')->willReturn($token);
        $this->authorizationChecker->method('isGranted')->with('IS_AUTHENTICATED_REMEMBERED')->willReturn(true);

        $this->assertNull($this->customerContext->getCustomer());
    }
}
