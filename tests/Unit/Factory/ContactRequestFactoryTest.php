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

namespace App\Tests\Unit\Factory;

use App\Context\CustomerContext;
use App\Entity\ContactRequest;
use App\Factory\ContactRequestFactory;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;

final class ContactRequestFactoryTest extends TestCase
{
    public function testItSetsEmailWithLoggedInCustomer(): void
    {
        $customer = $this->createStub(CustomerInterface::class);
        $customer->method('getEmail')->willReturn('d.vader@example.com');
        $customerContext = $this->createStub(CustomerContext::class);
        $customerContext->method('getCustomer')->willReturn($customer);

        $factory = new ContactRequestFactory(ContactRequest::class, $customerContext);

        $this->assertSame('d.vader@example.com', $factory->createNew()->getEmail());
    }
}
