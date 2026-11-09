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
use App\Entity\Article;
use App\Entity\Block;
use App\Factory\ArticleFactory;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ArticleFactoryTest extends TestCase
{
    private CustomerContext&Stub $customerContext;

    private FactoryInterface&Stub $blockFactory;

    private ArticleFactory $factory;

    protected function setUp(): void
    {
        $this->customerContext = $this->createStub(CustomerContext::class);
        $this->blockFactory = $this->createStub(FactoryInterface::class);
        $this->factory = new ArticleFactory(
            Article::class,
            $this->createStub(RepositoryInterface::class),
            $this->customerContext,
            $this->blockFactory,
        );
    }

    public function testItSetsAuthorWithLoggedInCustomer(): void
    {
        $customer = $this->createStub(CustomerInterface::class);
        $this->customerContext->method('getCustomer')->willReturn($customer);

        $this->assertSame($customer, $this->factory->createNew()->getAuthor());
    }

    public function testItCreatesAnArticleForProduct(): void
    {
        $product = $this->createStub(ProductInterface::class);

        $this->assertSame($product, $this->factory->createForProduct($product)->getProduct());
    }

    public function testItCreatesAReviewArticleForProduct(): void
    {
        $product = $this->createStub(ProductInterface::class);
        $product->method('getName')->willReturn('Puerto Rico');
        $this->blockFactory->method('createNew')->willReturnCallback(static fn (): Block => new Block());

        $this->assertSame($product, $this->factory->createReviewArticleForProduct($product)->getProduct());
    }
}
