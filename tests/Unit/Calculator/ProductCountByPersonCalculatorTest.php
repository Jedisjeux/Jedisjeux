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

namespace App\Tests\Unit\Calculator;

use App\Calculator\ProductCountByPersonCalculator;
use App\Entity\Person;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\ProductInterface;

final class ProductCountByPersonCalculatorTest extends TestCase
{
    private ProductCountByPersonCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new ProductCountByPersonCalculator();
    }

    public function testItCalculatesProductCountForADesigner(): void
    {
        $person = $this->createStub(Person::class);
        $person->method('getDesignerProducts')->willReturn(new ArrayCollection([
            $this->createStub(ProductInterface::class),
            $this->createStub(ProductInterface::class),
        ]));

        $this->assertSame(2, $this->calculator->calculateAsDesigner($person));
    }

    public function testItCalculatesProductCountForAArtist(): void
    {
        $person = $this->createStub(Person::class);
        $person->method('getArtistProducts')->willReturn(new ArrayCollection([
            $this->createStub(ProductInterface::class),
            $this->createStub(ProductInterface::class),
        ]));

        $this->assertSame(2, $this->calculator->calculateAsArtist($person));
    }

    public function testItCalculatesProductCountForAPublisher(): void
    {
        $person = $this->createStub(Person::class);
        $person->method('getPublisherProducts')->willReturn(new ArrayCollection([
            $this->createStub(ProductInterface::class),
            $this->createStub(ProductInterface::class),
        ]));

        $this->assertSame(2, $this->calculator->calculateAsPublisher($person));
    }
}
