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

namespace App\Tests\Unit\Grid\Filter;

use App\Grid\Filter\TaxonFilter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

final class TaxonFilterTest extends TestCase
{
    private DataSourceInterface&MockObject $dataSource;

    private ExpressionBuilderInterface&Stub $expressionBuilder;

    private TaxonRepositoryInterface&Stub $taxonRepository;

    private TaxonFilter $filter;

    protected function setUp(): void
    {
        $this->taxonRepository = $this->createStub(TaxonRepositoryInterface::class);
        $this->expressionBuilder = $this->createStub(ExpressionBuilderInterface::class);
        $this->dataSource = $this->createMock(DataSourceInterface::class);
        $this->dataSource->method('getExpressionBuilder')->willReturn($this->expressionBuilder);
        $this->filter = new TaxonFilter($this->taxonRepository, 'en_US');
    }

    public function testItImplementsFilterInterface(): void
    {
        $this->assertInstanceOf(FilterInterface::class, $this->filter);
    }

    public function testItDoesNotFilterOnTaxonByDefault(): void
    {
        $this->arrangeTaxon();

        $this->dataSource->expects($this->never())->method('restrict');

        $this->filter->apply($this->dataSource, 'resource', '', ['field' => 'taxon']);
    }

    public function testItFiltersOnTaxon(): void
    {
        $this->arrangeTaxon();

        $this->dataSource->expects($this->once())->method('restrict')->with('EXPR');

        $this->filter->apply($this->dataSource, 'resource', ['mainTaxon' => 'category'], ['field' => 'taxon']);
    }

    private function arrangeTaxon(): void
    {
        $rootTaxon = $this->createStub(TaxonInterface::class);
        $taxon = $this->createStub(TaxonInterface::class);
        $taxon->method('getLeft')->willReturn(2);
        $taxon->method('getRight')->willReturn(4);
        $taxon->method('getRoot')->willReturn($rootTaxon);
        $this->taxonRepository->method('findOneBySlug')->with('category', 'en_US')->willReturn($taxon);

        $this->expressionBuilder->method('greaterThanOrEqual')->with('taxon.left', 2)->willReturn('EXPR1');
        $this->expressionBuilder->method('lessThanOrEqual')->with('taxon.right', 4)->willReturn('EXPR2');
        $this->expressionBuilder->method('equals')->with('taxon.root', $rootTaxon)->willReturn('EXPR3');
        $this->expressionBuilder->method('andX')->with('EXPR1', 'EXPR2', 'EXPR3')->willReturn('EXPR');
    }
}
