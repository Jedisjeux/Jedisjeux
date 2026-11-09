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

use App\Calculator\TopicCountByTaxonCalculator;
use App\Repository\TopicRepository;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

final class TopicCountByTaxonCalculatorTest extends TestCase
{
    public function testItCalculatesTopicCountForATaxon(): void
    {
        $taxon = $this->createStub(TaxonInterface::class);
        $repository = $this->createStub(TopicRepository::class);
        $repository->method('countByTaxon')->with($taxon)->willReturn(3);

        $this->assertSame(3, (new TopicCountByTaxonCalculator($repository))->calculate($taxon));
    }
}
