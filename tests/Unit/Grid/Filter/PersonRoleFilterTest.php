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

use App\Grid\Filter\PersonRoleFilter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

final class PersonRoleFilterTest extends TestCase
{
    private DataSourceInterface&MockObject $dataSource;

    private ExpressionBuilderInterface&Stub $expressionBuilder;

    private PersonRoleFilter $filter;

    protected function setUp(): void
    {
        $this->expressionBuilder = $this->createStub(ExpressionBuilderInterface::class);
        $this->dataSource = $this->createMock(DataSourceInterface::class);
        $this->dataSource->method('getExpressionBuilder')->willReturn($this->expressionBuilder);
        $this->filter = new PersonRoleFilter();
    }

    public function testItImplementsFilterInterface(): void
    {
        $this->assertInstanceOf(FilterInterface::class, $this->filter);
    }

    public function testItDoesNotFilterOnPersonRoleByDefault(): void
    {
        $this->expressionBuilder->method('greaterThan')->willReturn('EXPR');

        $this->dataSource->expects($this->never())->method('restrict');

        $this->filter->apply($this->dataSource, 'person', '', []);
    }

    public function testItDoesNotFilterWhenRoleWasNotFound(): void
    {
        $this->expressionBuilder->method('greaterThan')->willReturn('EXPR');

        $this->dataSource->expects($this->never())->method('restrict');

        $this->filter->apply($this->dataSource, 'person', 'developers', []);
    }

    public function testItFiltersOnDesigners(): void
    {
        $this->expressionBuilder->method('greaterThan')->with('productCountAsDesigner', 0)->willReturn('EXPR');

        $this->dataSource->expects($this->once())->method('restrict')->with('EXPR');

        $this->filter->apply($this->dataSource, 'person', 'designers', []);
    }

    public function testItFiltersOnArtists(): void
    {
        $this->expressionBuilder->method('greaterThan')->with('productCountAsArtist', 0)->willReturn('EXPR');

        $this->dataSource->expects($this->once())->method('restrict')->with('EXPR');

        $this->filter->apply($this->dataSource, 'person', 'artists', []);
    }

    public function testItFiltersOnPublishers(): void
    {
        $this->expressionBuilder->method('greaterThan')->with('productCountAsPublisher', 0)->willReturn('EXPR');

        $this->dataSource->expects($this->once())->method('restrict')->with('EXPR');

        $this->filter->apply($this->dataSource, 'person', 'publishers', []);
    }
}
