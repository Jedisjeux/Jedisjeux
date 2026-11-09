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

namespace App\Tests\Unit\Validator\Initializer;

use App\Validator\Initializer\CustomerInitializer;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\User\Canonicalizer\CanonicalizerInterface;
use Symfony\Component\Validator\ObjectInitializerInterface;

final class CustomerInitializerTest extends TestCase
{
    private CanonicalizerInterface&Stub $canonicalizer;

    private CustomerInitializer $initializer;

    protected function setUp(): void
    {
        $this->canonicalizer = $this->createStub(CanonicalizerInterface::class);
        $this->initializer = new CustomerInitializer($this->canonicalizer);
    }

    public function testItImplementsSymfonyValidatorInitializerInterface(): void
    {
        $this->assertInstanceOf(ObjectInitializerInterface::class, $this->initializer);
    }

    public function testItSetsCanonicalEmailWhenInitializingCustomer(): void
    {
        $customer = $this->createMock(CustomerInterface::class);
        $customer->method('getEmail')->willReturn('sTeFfEn@gMaiL.CoM');
        $this->canonicalizer->method('canonicalize')->with('sTeFfEn@gMaiL.CoM')->willReturn('steffen@gmail.com');

        $customer->expects($this->once())->method('setEmailCanonical')->with('steffen@gmail.com');

        $this->initializer->initialize($customer);
    }

    public function testItDoesNotSetCanonicalEmailWhenInitializingNonCustomerObject(): void
    {
        $object = new \stdClass();

        $this->initializer->initialize($object);

        $this->assertEquals(new \stdClass(), $object);
    }
}
