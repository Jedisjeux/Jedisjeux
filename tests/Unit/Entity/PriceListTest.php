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
use App\Entity\PriceList;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Model\ResourceInterface;

final class PriceListTest extends TestCase
{
    private PriceList $sut;

    protected function setUp(): void
    {
        $this->sut = new PriceList();
    }

    public function testImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->sut);
    }

    public function testHasNoPathByDefault(): void
    {
        $this->assertSame(null, $this->sut->getPath());
    }

    public function testItsPathIsMutable(): void
    {
        $this->assertNull($this->sut->setPath('/path/to/file.csv'));
        $this->assertSame('/path/to/file.csv', $this->sut->getPath());
    }

    public function testHasNoHeadersByDefault(): void
    {
        $this->assertFalse($this->sut->hasHeaders());
    }

    public function testCanHaveHeaders(): void
    {
        $this->sut->setHeaders(false);
        $this->sut->setHeaders(true);

        $this->assertTrue($this->sut->hasHeaders());
    }

    public function testHasSemicolonAsDelimiterByDefault(): void
    {
        $this->assertSame(";", $this->sut->getDelimiter());
    }

    public function testItsDelimiterIsMutable(): void
    {
        $this->sut->setDelimiter(",");

        $this->assertSame(",", $this->sut->getDelimiter());
    }

    public function testItsCharsetIsUtf8ByDefault(): void
    {
        $this->assertTrue($this->sut->isUtf8());
    }

    public function testItsCharsetIsMutable(): void
    {
        $this->sut->setUtf8(false);

        $this->assertFalse($this->sut->isUtf8());
    }

    public function testIsNotActiveByDefault(): void
    {
        $this->assertFalse($this->sut->isActive());
    }

    public function testCanBeActive(): void
    {
        $this->sut->setActive(true);

        $this->assertTrue($this->sut->isActive());
    }

    public function testHasNoDealerByDefault(): void
    {
        $this->assertSame(null, $this->sut->getDealer());
    }

    public function testItsDealerIsMutable(): void
    {
        $obj = $this->createStub(Dealer::class);

        $this->sut->setDealer($obj);

        $this->assertSame($obj, $this->sut->getDealer());
    }
}
