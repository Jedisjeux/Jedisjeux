<?php

namespace spec\App\Grid\Filter;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

class GreaterThanOrEqualFilterSpec extends ObjectBehavior
{
    function it_implements_filter_interface(): void
    {
        $this->shouldImplement(FilterInterface::class);
    }

    function it_does_not_filter_by_default(
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder
    ): void {
        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->greaterThanOrEqual('minAge', '')->willReturn('EXPR');
        $dataSource->restrict('EXPR')->shouldNotBeCalled();

        $this->apply($dataSource, 'product', '', ['field' => 'minAge']);
    }

    function it_filters_on_field_value_greater_than_or_equal_value(
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder
    ): void {
        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->greaterThanOrEqual('minAge', 12)->willReturn('EXPR');
        $dataSource->restrict('EXPR')->shouldBeCalled();

        $this->apply($dataSource, 'product', ['value' => 12], ['field' => 'minAge']);
    }
}
