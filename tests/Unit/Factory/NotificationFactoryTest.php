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

use App\Entity\Article;
use App\Entity\Notification;
use App\Entity\ProductBox;
use App\Entity\ProductFile;
use App\Factory\NotificationFactory;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;

final class NotificationFactoryTest extends TestCase
{
    private NotificationFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new NotificationFactory(Notification::class);
    }

    public function testItCanCreateANotification(): void
    {
        $this->assertInstanceOf(Notification::class, $this->factory->createNew());
    }

    public function testItCanCreateANotificationForACustomer(): void
    {
        $customer = $this->createStub(CustomerInterface::class);

        $this->assertSame($customer, $this->factory->createForCustomer($customer)->getRecipient());
    }

    public function testItCanCreateANotificationForAProduct(): void
    {
        $product = $this->createStub(ProductInterface::class);
        $customer = $this->createStub(CustomerInterface::class);

        $this->assertSame($product, $this->factory->createForProduct($product, $customer)->getProduct());
    }

    public function testItCanCreateANotificationForAnArticle(): void
    {
        $article = $this->createStub(Article::class);
        $customer = $this->createStub(CustomerInterface::class);

        $this->assertSame($article, $this->factory->createForArticle($article, $customer)->getArticle());
    }

    public function testItCanCreateANotificationForAProductBox(): void
    {
        $productBox = $this->createStub(ProductBox::class);
        $customer = $this->createStub(CustomerInterface::class);

        $this->assertSame($productBox, $this->factory->createForProductBox($productBox, $customer)->getProductBox());
    }

    public function testItCanCreateANotificationForAProductFile(): void
    {
        $productFile = $this->createStub(ProductFile::class);
        $customer = $this->createStub(CustomerInterface::class);

        $this->assertSame($productFile, $this->factory->createForProductFile($productFile, $customer)->getProductFile());
    }
}
