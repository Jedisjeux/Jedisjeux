<?php

namespace spec\App\Grid\Filter;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

class PersonRoleFilterSpec extends ObjectBehavior
{
    function it_implements_filter_interface(): void
    {
        $this->shouldImplement(FilterInterface::class);
    }

    function it_does_not_filter_on_person_role_by_default(
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder
    ): void {
        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->greaterThan('productCountAsDesigner', 0)->willReturn('EXPR');
        $expressionBuilder->greaterThan('productCountAsArtist', 0)->willReturn('EXPR');
        $expressionBuilder->greaterThan('productCountAsPublisher', 0)->willReturn('EXPR');
        $dataSource->restrict('EXPR')->shouldNotBeCalled();

        $this->apply($dataSource, 'person', '', []);
    }

    function it_does_not_filter_when_role_was_not_found(
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder
    ): void {
        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->greaterThan('productCountAsDesigner', 0)->willReturn('EXPR');
        $expressionBuilder->greaterThan('productCountAsArtist', 0)->willReturn('EXPR');
        $expressionBuilder->greaterThan('productCountAsPublisher', 0)->willReturn('EXPR');
        $dataSource->restrict('EXPR')->shouldNotBeCalled();

        $this->apply($dataSource, 'person', 'developers', []);
    }

    function it_filters_on_designers(
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder
    ): void {
        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->greaterThan('productCountAsDesigner', 0)->willReturn('EXPR');
        $dataSource->restrict('EXPR')->shouldBeCalled();

        $this->apply($dataSource, 'person', 'designers', []);
    }

    function it_filters_on_artists(
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder
    ): void {
        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->greaterThan('productCountAsArtist', 0)->willReturn('EXPR');
        $dataSource->restrict('EXPR')->shouldBeCalled();

        $this->apply($dataSource, 'person', 'artists', []);
    }

    function it_filters_on_publishers(
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder
    ): void {
        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->greaterThan('productCountAsPublisher', 0)->willReturn('EXPR');
        $dataSource->restrict('EXPR')->shouldBeCalled();

        $this->apply($dataSource, 'person', 'publishers', []);
    }
}
