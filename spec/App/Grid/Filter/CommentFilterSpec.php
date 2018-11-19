<?php

namespace spec\App\Grid\Filter;

use App\Grid\Filter\CommentFilter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

class CommentFilterSpec extends ObjectBehavior
{
    function it_implements_filter_interface(): void
    {
        $this->shouldImplement(FilterInterface::class);
    }

    function it_does_not_filter_on_topic_comments_by_default(
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder
    ): void {
        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->isNull('topic')->willReturn('EXPR');
        $expressionBuilder->isNotNull('topic')->willReturn('EXPR');
        $dataSource->restrict('EXPR')->shouldNotBeCalled();

        $this->apply($dataSource, 'topic', '', []);
    }

    function it_filters_on_topic_with_comments(
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder
    ): void {
        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->isNotNull('topic')->willReturn('EXPR');
        $dataSource->restrict('EXPR')->shouldBeCalled();

        $this->apply($dataSource, 'topic', 'with', []);
    }

    function it_filters_on_topic_without_comments(
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder
    ): void {
        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->isNull('topic')->willReturn('EXPR');
        $dataSource->restrict('EXPR')->shouldBeCalled();

        $this->apply($dataSource, 'topic', 'without', []);
    }

    function it_does_not_filter_on_topic_comments(
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder
    ): void {
        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->isNull('topic')->willReturn('EXPR');
        $expressionBuilder->isNotNull('topic')->willReturn('EXPR');
        $dataSource->restrict('EXPR')->shouldNotBeCalled();

        $this->apply($dataSource, 'topic', 'all', []);
    }
}
