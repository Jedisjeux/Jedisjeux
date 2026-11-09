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

namespace App\Tests\Unit\Guard;

use App\Guard\ProductGuard;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class ProductGuardTest extends TestCase
{
    public function testItReturnsIfCurrentUserIsGrantedToAskForAPublication(): void
    {
        $this->assertTrue($this->createGuard('ROLE_REVIEWER', true)->canAskForPublication());
        $this->assertFalse($this->createGuard('ROLE_REVIEWER', false)->canAskForPublication());
    }

    public function testItReturnsIfCurrentUserIsGrantedToPublish(): void
    {
        $this->assertTrue($this->createGuard('ROLE_PUBLISHER', true)->canPublish());
        $this->assertFalse($this->createGuard('ROLE_PUBLISHER', false)->canPublish());
    }

    private function createGuard(string $role, bool $granted): ProductGuard
    {
        $authorizationChecker = $this->createStub(AuthorizationCheckerInterface::class);
        $authorizationChecker->method('isGranted')->willReturnMap([[$role, null, $granted]]);

        return new ProductGuard($authorizationChecker);
    }
}
