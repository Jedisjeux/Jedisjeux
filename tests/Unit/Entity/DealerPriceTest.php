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

use App\Entity\Dealer;
use App\Entity\DealerPrice;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

final class DealerPriceTest extends TestCase
{
    private DealerPrice $sut;

    protected function setUp(): void
    {
        $this->sut = new DealerPrice();
    }

    public function testItHasNoDealerByDefault(): void
    {
        $this->assertNull($this->sut->getDealer());
    }

    public function testItsDealerIsMutable(): void
    {
        $dealer = $this->createStub(Dealer::class);

        $this->sut->setDealer($dealer);
        $this->assertSame($dealer, $this->sut->getDealer());
    }

    public function testItHasNoProductByDefault(): void
    {
        $this->assertNull($this->sut->getProduct());
    }

    public function testItsProductIsMutable(): void
    {
        $product = $this->createStub(Product::class);

        $this->sut->setProduct($product);
        $this->assertSame($product, $this->sut->getProduct());
    }

    public function testItHasNoUrlByDefault(): void
    {
        $this->assertNull($this->sut->getUrl());
    }

    public function testItsUrlIsMutable(): void
    {
        $this->sut->setUrl('http://example.com');
        $this->assertSame('http://example.com', $this->sut->getUrl());
    }

    public function testItHasNoNameByDefault(): void
    {
        $this->assertNull($this->sut->getName());
    }

    public function testItsNameIsMutable(): void
    {
        $this->sut->setName('Puerto Rico');
        $this->assertSame('Puerto Rico', $this->sut->getName());
    }

    public function testItHasNoPriceByDefault(): void
    {
        $this->assertNull($this->sut->getPrice());
    }

    public function testItsPriceIsMutable(): void
    {
        $this->sut->setPrice(2200);
        $this->assertSame(2200, $this->sut->getPrice());
    }

    public function testItHasNoBarcodeByDefault(): void
    {
        $this->assertNull($this->sut->getBarcode());
    }

    public function testItsBarcodeIsMutable(): void
    {
        $this->sut->setBarcode('0123456789');
        $this->assertSame('0123456789', $this->sut->getBarcode());
    }

    public function testItHasNoStatusByDefault(): void
    {
        $this->assertNull($this->sut->getStatus());
    }

    public function testItsStatusIsMutable(): void
    {
        $this->sut->setStatus(DealerPrice::STATUS_AVAILABLE);
        $this->assertSame(DealerPrice::STATUS_AVAILABLE, $this->sut->getStatus());
    }
}
