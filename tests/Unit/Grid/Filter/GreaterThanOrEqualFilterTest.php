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

use App\Grid\Filter\GreaterThanOrEqualFilter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

final class GreaterThanOrEqualFilterTest extends TestCase
{
    private DataSourceInterface&MockObject $dataSource;

    private ExpressionBuilderInterface&Stub $expressionBuilder;

    private GreaterThanOrEqualFilter $filter;

    protected function setUp(): void
    {
        $this->expressionBuilder = $this->createStub(ExpressionBuilderInterface::class);
        $this->dataSource = $this->createMock(DataSourceInterface::class);
        $this->dataSource->method('getExpressionBuilder')->willReturn($this->expressionBuilder);
        $this->filter = new GreaterThanOrEqualFilter();
    }

    public function testItImplementsFilterInterface(): void
    {
        $this->assertInstanceOf(FilterInterface::class, $this->filter);
    }

    public function testItDoesNotFilterByDefault(): void
    {
        $this->expressionBuilder->method('greaterThanOrEqual')->with('minAge', '')->willReturn('EXPR');

        $this->dataSource->expects($this->never())->method('restrict');

        $this->filter->apply($this->dataSource, 'product', '', ['field' => 'minAge']);
    }

    public function testItFiltersOnFieldValueGreaterThanOrEqualValue(): void
    {
        $this->expressionBuilder->method('greaterThanOrEqual')->with('minAge', 12)->willReturn('EXPR');

        $this->dataSource->expects($this->once())->method('restrict')->with('EXPR');

        $this->filter->apply($this->dataSource, 'product', ['value' => 12], ['field' => 'minAge']);
    }
}
