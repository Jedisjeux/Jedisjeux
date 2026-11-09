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

use App\Entity\ProductBox;
use App\Entity\ProductInterface;
use App\Factory\ProductBoxFactory;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class ProductBoxFactoryTest extends TestCase
{
    private FactoryInterface&Stub $decoratedFactory;

    private CustomerContextInterface&Stub $customerContext;

    private ProductBoxFactory $factory;

    protected function setUp(): void
    {
        $this->decoratedFactory = $this->createStub(FactoryInterface::class);
        $this->customerContext = $this->createStub(CustomerContextInterface::class);
        $this->factory = new ProductBoxFactory($this->decoratedFactory, $this->customerContext);
    }

    public function testItImplementsFactoryInterface(): void
    {
        $this->assertInstanceOf(FactoryInterface::class, $this->factory);
    }

    public function testItCreatesProductBoxes(): void
    {
        $productBox = $this->createStub(ProductBox::class);
        $this->decoratedFactory->method('createNew')->willReturn($productBox);

        $this->assertSame($productBox, $this->factory->createNew());
    }

    public function testItCreatesProductBoxesForAProduct(): void
    {
        $productBox = $this->createMock(ProductBox::class);
        $product = $this->createStub(ProductInterface::class);
        $this->decoratedFactory->method('createNew')->willReturn($productBox);

        $productBox->expects($this->once())->method('setProduct')->with($product);
        $productBox->expects($this->once())->method('setAuthor')->with(null);

        $this->factory->createForProduct($product);
    }

    public function testItSetsAuthorWithCurrentCustomer(): void
    {
        $productBox = $this->createMock(ProductBox::class);
        $author = $this->createStub(CustomerInterface::class);
        $this->decoratedFactory->method('createNew')->willReturn($productBox);
        $this->customerContext->method('getCustomer')->willReturn($author);

        $productBox->expects($this->once())->method('setAuthor')->with($author);

        $this->assertSame($productBox, $this->factory->createNew());
    }
}
