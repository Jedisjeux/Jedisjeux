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
use App\Entity\ProductList;
use App\Factory\ProductListFactory;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ProductListFactoryTest extends TestCase
{
    private CustomerContext&Stub $customerContext;

    private TranslatorInterface&Stub $translator;

    private ProductListFactory $factory;

    protected function setUp(): void
    {
        $this->customerContext = $this->createStub(CustomerContext::class);
        $this->translator = $this->createStub(TranslatorInterface::class);
        $this->factory = new ProductListFactory(ProductList::class, $this->customerContext, $this->translator);
    }

    public function testItSetsOwner(): void
    {
        $customer = $this->createStub(CustomerInterface::class);
        $this->customerContext->method('getCustomer')->willReturn($customer);

        $this->assertSame($customer, $this->factory->createNew()->getOwner());
    }

    public function testItCreatesAListForCode(): void
    {
        $this->translator->method('trans')->with('app.ui.XYZ')->willReturn('XYZ list');

        $this->assertSame('XYZ', $this->factory->createForCode('XYZ')->getCode());
    }
}
