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

use App\Calculator\TopicCountByTaxonCalculator;
use App\Entity\Taxon;
use App\Updater\TopicCountByTaxonUpdater;
use PHPUnit\Framework\TestCase;

final class TopicCountByTaxonUpdaterTest extends TestCase
{
    public function testItUpdatesTaxonWithTopicCount(): void
    {
        $taxon = $this->createMock(Taxon::class);
        $calculator = $this->createStub(TopicCountByTaxonCalculator::class);
        $calculator->method('calculate')->with($taxon)->willReturn(5);

        $taxon->expects($this->once())->method('setTopicCount')->with(5);

        (new TopicCountByTaxonUpdater($calculator))->update($taxon);
    }
}
