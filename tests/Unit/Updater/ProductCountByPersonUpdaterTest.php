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

use App\Calculator\ProductCountByPersonCalculator;
use App\Entity\Person;
use App\Updater\ProductCountByPersonUpdater;
use PHPUnit\Framework\TestCase;

final class ProductCountByPersonUpdaterTest extends TestCase
{
    public function testItUpdatesProductCountForAPerson(): void
    {
        $person = $this->createMock(Person::class);
        $calculator = $this->createStub(ProductCountByPersonCalculator::class);
        $calculator->method('calculateAsDesigner')->with($person)->willReturn(3);
        $calculator->method('calculateAsArtist')->with($person)->willReturn(4);
        $calculator->method('calculateAsPublisher')->with($person)->willReturn(5);

        $person->expects($this->once())->method('setProductCountAsDesigner')->with(3);
        $person->expects($this->once())->method('setProductCountAsArtist')->with(4);
        $person->expects($this->once())->method('setProductCountAsPublisher')->with(5);
        $person->expects($this->once())->method('setProductCount')->with(12);

        (new ProductCountByPersonUpdater($calculator))->update($person);
    }
}
