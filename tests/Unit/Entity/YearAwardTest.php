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

use App\Entity\GameAward;
use App\Entity\YearAward;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class YearAwardTest extends TestCase
{
    private YearAward $yearAward;

    protected function setUp(): void
    {
        $this->yearAward = new YearAward();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->yearAward);
    }

    public function testItHasNoIdByDefault(): void
    {
        $this->assertNull($this->yearAward->getId());
    }

    public function testItHasNoYearByDefault(): void
    {
        $this->assertNull($this->yearAward->getYear());
    }

    public function testItsYearIsMutable(): void
    {
        $this->yearAward->setYear('2018');

        $this->assertSame('2018', $this->yearAward->getYear());
    }

    public function testItHasNoAwardByDefault(): void
    {
        $this->assertNull($this->yearAward->getAward());
    }

    public function testItsAwardIsMutable(): void
    {
        $award = $this->createStub(GameAward::class);

        $this->yearAward->setAward($award);

        $this->assertSame($award, $this->yearAward->getAward());
    }

    public function testItHasNoProductByDefault(): void
    {
        $this->assertNull($this->yearAward->getProduct());
    }

    public function testItsProductIsMutable(): void
    {
        $product = $this->createStub(ProductInterface::class);

        $this->yearAward->setProduct($product);

        $this->assertSame($product, $this->yearAward->getProduct());
    }

    public function testItHasNoNameByDefault(): void
    {
        $this->assertNull($this->yearAward->getName());
    }

    public function testItCanGetName(): void
    {
        $award = $this->createStub(GameAward::class);
        $award->method('getName')->willReturn('Spiel des Jahres');

        $this->yearAward->setYear('2018');
        $this->yearAward->setAward($award);

        $this->assertSame('Spiel des Jahres 2018', $this->yearAward->getName());
    }
}
