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
use App\Entity\GamePlay;
use App\Factory\GamePlayFactory;
use App\Repository\ProductRepository;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;

final class GamePlayFactoryTest extends TestCase
{
    private ProductRepository&Stub $productRepository;

    private CustomerContext&Stub $customerContext;

    private GamePlayFactory $factory;

    protected function setUp(): void
    {
        $this->productRepository = $this->createStub(ProductRepository::class);
        $this->customerContext = $this->createStub(CustomerContext::class);
        $this->factory = new GamePlayFactory(GamePlay::class, $this->productRepository, $this->customerContext);
    }

    public function testItSetAuthorWithCurrentCustomer(): void
    {
        $customer = $this->createStub(CustomerInterface::class);
        $this->customerContext->method('getCustomer')->willReturn($customer);

        $this->assertSame($customer, $this->factory->createNew()->getAuthor());
    }

    public function testItCreatesForProduct(): void
    {
        $product = $this->createStub(ProductInterface::class);
        $this->productRepository->method('findOneBySlug')->with('en_US', 'puerto-rico')->willReturn($product);

        $this->assertSame($product, $this->factory->createForProduct('en_US', 'puerto-rico')->getProduct());
    }
}
