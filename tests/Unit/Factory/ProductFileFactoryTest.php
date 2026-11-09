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

use App\Entity\ProductFile;
use App\Entity\ProductInterface;
use App\Factory\ProductFileFactory;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface as BaseCustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class ProductFileFactoryTest extends TestCase
{
    private FactoryInterface&Stub $decoratedFactory;

    private CustomerContextInterface&Stub $customerContext;

    private ProductFileFactory $factory;

    protected function setUp(): void
    {
        $this->decoratedFactory = $this->createStub(FactoryInterface::class);
        $this->customerContext = $this->createStub(CustomerContextInterface::class);
        $this->factory = new ProductFileFactory($this->decoratedFactory, $this->customerContext);
    }

    public function testItImplementsFactoryInterface(): void
    {
        $this->assertInstanceOf(FactoryInterface::class, $this->factory);
    }

    public function testItCreatesProductBoxes(): void
    {
        $productFile = $this->createStub(ProductFile::class);
        $this->decoratedFactory->method('createNew')->willReturn($productFile);

        $this->assertSame($productFile, $this->factory->createNew());
    }

    public function testItCreatesProductBoxesForAProduct(): void
    {
        $productBox = $this->createMock(ProductFile::class);
        $product = $this->createStub(ProductInterface::class);
        $this->decoratedFactory->method('createNew')->willReturn($productBox);

        $productBox->expects($this->once())->method('setProduct')->with($product);
        $productBox->expects($this->once())->method('setAuthor')->with(null);

        $this->factory->createForProduct($product);
    }

    public function testItThrowsAnInvalidArgumentExceptionWhenCustomerExtendsAWrongCustomerModel(): void
    {
        $this->customerContext->method('getCustomer')->willReturn($this->createStub(BaseCustomerInterface::class));

        $this->expectException(\InvalidArgumentException::class);

        $this->factory->createNew();
    }
}
