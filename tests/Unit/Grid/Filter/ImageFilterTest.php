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

use App\Grid\Filter\ImageFilter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

final class ImageFilterTest extends TestCase
{
    private DataSourceInterface&MockObject $dataSource;

    private ExpressionBuilderInterface&Stub $expressionBuilder;

    private ImageFilter $filter;

    protected function setUp(): void
    {
        $this->expressionBuilder = $this->createStub(ExpressionBuilderInterface::class);
        $this->dataSource = $this->createMock(DataSourceInterface::class);
        $this->dataSource->method('getExpressionBuilder')->willReturn($this->expressionBuilder);
        $this->filter = new ImageFilter();
    }

    public function testItImplementsFilterInterface(): void
    {
        $this->assertInstanceOf(FilterInterface::class, $this->filter);
    }

    public function testItDoesNotFilterOnResourcesWithImagesByDefault(): void
    {
        $this->expressionBuilder->method('greaterThan')->willReturn('EXPR');
        $this->expressionBuilder->method('equals')->willReturn('EXPR');

        $this->dataSource->expects($this->never())->method('restrict');

        $this->filter->apply($this->dataSource, 'topic', '', []);
    }

    public function testItFiltersOnResourcesWithImages(): void
    {
        $this->expressionBuilder->method('greaterThan')->with('imageCount', 0)->willReturn('EXPR');

        $this->dataSource->expects($this->once())->method('restrict')->with('EXPR');

        $this->filter->apply($this->dataSource, 'resource', 'with', []);
    }

    public function testItFiltersOnResourcesWithoutImages(): void
    {
        $this->expressionBuilder->method('equals')->with('imageCount', 0)->willReturn('EXPR');

        $this->dataSource->expects($this->once())->method('restrict')->with('EXPR');

        $this->filter->apply($this->dataSource, 'resource', 'without', []);
    }
}
