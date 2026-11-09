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

namespace App\Tests\Unit\Entity;

use App\Entity\ProductBox;
use App\Entity\ProductBoxImage;
use App\Entity\ProductInterface;
use App\Entity\ProductVariantInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class ProductBoxTest extends TestCase
{
    private ProductBox $box;

    protected function setUp(): void
    {
        $this->box = new ProductBox();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->box);
    }

    public function testItIsNewByDefault(): void
    {
        $this->assertSame(ProductBox::STATUS_NEW, $this->box->getStatus());
    }

    public function testItCanBeAccepted(): void
    {
        $this->box->setStatus(ProductBox::STATUS_ACCEPTED);

        $this->assertSame(ProductBox::STATUS_ACCEPTED, $this->box->getStatus());
    }

    public function testItCanBeRejected(): void
    {
        $this->box->setStatus(ProductBox::STATUS_REJECTED);

        $this->assertSame(ProductBox::STATUS_REJECTED, $this->box->getStatus());
    }

    public function testItIsNotEnabledByDefault(): void
    {
        $this->assertFalse($this->box->isEnabled());
    }

    public function testItIsToggleable(): void
    {
        $this->box->enable();
        $this->assertTrue($this->box->isEnabled());

        $this->box->disable();
        $this->assertFalse($this->box->isEnabled());
    }

    public function testItHasNoHeightByDefault(): void
    {
        $this->assertNull($this->box->getHeight());
    }

    public function testItsHeightIsMutable(): void
    {
        $this->box->setHeight(42);

        $this->assertSame(42, $this->box->getHeight());
    }

    public function testItHasNoRealHeightByDefault(): void
    {
        $this->assertNull($this->box->getRealHeight());
    }

    public function testItsRealHeightIsMutable(): void
    {
        $this->box->setRealHeight(420);

        $this->assertSame(420, $this->box->getRealHeight());
        $this->assertSame(271, $this->box->getHeight());
    }

    public function testItHasNoImageByDefault(): void
    {
        $this->assertNull($this->box->getImage());
    }

    public function testItsImageIsMutable(): void
    {
        $image = $this->createStub(ProductBoxImage::class);

        $this->box->setImage($image);

        $this->assertSame($image, $this->box->getImage());
    }

    public function testItHasNoProductByDefault(): void
    {
        $this->assertNull($this->box->getProduct());
    }

    public function testItsProductIsMutable(): void
    {
        $variant = $this->createStub(ProductVariantInterface::class);
        $product = $this->createStub(ProductInterface::class);
        $product->method('getFirstVariant')->willReturn($variant);

        $this->box->setProduct($product);

        $this->assertSame($product, $this->box->getProduct());
        $this->assertSame($variant, $this->box->getProductVariant());
    }

    public function testItHasNoProductVariantByDefault(): void
    {
        $this->assertNull($this->box->getProductVariant());
    }

    public function testItsProductVariantIsMutable(): void
    {
        $variant = $this->createStub(ProductVariantInterface::class);

        $this->box->setProductVariant($variant);

        $this->assertSame($variant, $this->box->getProductVariant());
    }

    public function testItHasNoAuthorByDefault(): void
    {
        $this->assertNull($this->box->getAuthor());
    }

    public function testItsAuthorIsMutable(): void
    {
        $author = $this->createStub(CustomerInterface::class);

        $this->box->setAuthor($author);

        $this->assertSame($author, $this->box->getAuthor());
    }
}
