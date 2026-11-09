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

use App\Grid\Filter\CommentFilter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

final class CommentFilterTest extends TestCase
{
    private DataSourceInterface&MockObject $dataSource;

    private ExpressionBuilderInterface&Stub $expressionBuilder;

    private CommentFilter $filter;

    protected function setUp(): void
    {
        $this->expressionBuilder = $this->createStub(ExpressionBuilderInterface::class);
        $this->dataSource = $this->createMock(DataSourceInterface::class);
        $this->dataSource->method('getExpressionBuilder')->willReturn($this->expressionBuilder);
        $this->filter = new CommentFilter();
    }

    public function testItImplementsFilterInterface(): void
    {
        $this->assertInstanceOf(FilterInterface::class, $this->filter);
    }

    public function testItDoesNotFilterOnTopicCommentsByDefault(): void
    {
        $this->expressionBuilder->method('isNull')->willReturn('EXPR');
        $this->expressionBuilder->method('isNotNull')->willReturn('EXPR');

        $this->dataSource->expects($this->never())->method('restrict');

        $this->filter->apply($this->dataSource, 'topic', '', []);
    }

    public function testItFiltersOnTopicWithComments(): void
    {
        $this->expressionBuilder->method('isNotNull')->with('topic')->willReturn('EXPR');

        $this->dataSource->expects($this->once())->method('restrict')->with('EXPR');

        $this->filter->apply($this->dataSource, 'topic', 'with', []);
    }

    public function testItFiltersOnTopicWithoutComments(): void
    {
        $this->expressionBuilder->method('isNull')->with('topic')->willReturn('EXPR');

        $this->dataSource->expects($this->once())->method('restrict')->with('EXPR');

        $this->filter->apply($this->dataSource, 'topic', 'without', []);
    }

    public function testItDoesNotFilterOnTopicComments(): void
    {
        $this->expressionBuilder->method('isNull')->willReturn('EXPR');
        $this->expressionBuilder->method('isNotNull')->willReturn('EXPR');

        $this->dataSource->expects($this->never())->method('restrict');

        $this->filter->apply($this->dataSource, 'topic', 'all', []);
    }
}
