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

use App\Entity\ProductBarcode;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class ProductBarcodeTest extends TestCase
{
    private ProductBarcode $barcode;

    protected function setUp(): void
    {
        $this->barcode = new ProductBarcode();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->barcode);
    }

    public function testItHasNoCodeByDefault(): void
    {
        $this->assertNull($this->barcode->getCode());
    }

    public function testItsCodeIsMutable(): void
    {
        $this->barcode->setCode('123456789');

        $this->assertSame('123456789', $this->barcode->getCode());
    }

    public function testItHasNoProductByDefault(): void
    {
        $this->assertNull($this->barcode->getProduct());
    }

    public function testItsProductIsMutable(): void
    {
        $product = $this->createStub(ProductInterface::class);

        $this->barcode->setProduct($product);

        $this->assertSame($product, $this->barcode->getProduct());
    }
}
