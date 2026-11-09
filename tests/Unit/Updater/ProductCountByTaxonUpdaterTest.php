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

namespace App\Tests\Unit\Updater;

use App\Calculator\ProductCountByTaxonCalculator;
use App\Entity\Taxon;
use App\Updater\ProductCountByTaxonUpdater;
use PHPUnit\Framework\TestCase;

final class ProductCountByTaxonUpdaterTest extends TestCase
{
    public function testItUpdatesTaxonWithProductCount(): void
    {
        $taxon = $this->createMock(Taxon::class);
        $calculator = $this->createStub(ProductCountByTaxonCalculator::class);
        $calculator->method('calculate')->with($taxon)->willReturn(5);

        $taxon->expects($this->once())->method('setProductCount')->with(5);

        (new ProductCountByTaxonUpdater($calculator))->update($taxon);
    }
}
