<?php

namespace spec\App\Grid\Filter;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

class TaxonFilterSpec extends ObjectBehavior
{
    function let(TaxonRepositoryInterface $taxonRepository)
    {
        $this->beConstructedWith($taxonRepository, 'en_US');
    }

    function it_implements_filter_interface(): void
    {
        $this->shouldImplement(FilterInterface::class);
    }

    function it_does_not_filter_on_taxon_by_default(
        TaxonRepositoryInterface $taxonRepository,
        TaxonInterface $taxon,
        TaxonInterface $rootTaxon,
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder
    ): void {
        $taxonRepository->findOneBySlug('category', 'en_US')->willReturn($taxon);

        $taxon->getLeft()->willReturn(2);
        $taxon->getRight()->willReturn(4);
        $taxon->getRoot()->willReturn($rootTaxon);

        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->andX('EXPR1', 'EXPR2', 'EXPR3')->willReturn('EXPR');
        $expressionBuilder->greaterThanOrEqual('taxon.left', 2)->willReturn('EXPR1');
        $expressionBuilder->lessThanOrEqual('taxon.right', 4)->willReturn('EXPR2');
        $expressionBuilder->equals('taxon.root', $rootTaxon)->willReturn('EXPR3');

        $dataSource->restrict('EXPR')->shouldNotBeCalled();

        $this->apply($dataSource, 'resource', '', ['field' => 'taxon']);
    }

    function it_filters_on_taxon(
        TaxonRepositoryInterface $taxonRepository,
        TaxonInterface $taxon,
        TaxonInterface $rootTaxon,
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder
    ): void {
        $taxonRepository->findOneBySlug('category', 'en_US')->willReturn($taxon);

        $taxon->getLeft()->willReturn(2);
        $taxon->getRight()->willReturn(4);
        $taxon->getRoot()->willReturn($rootTaxon);

        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->andX('EXPR1', 'EXPR2', 'EXPR3')->willReturn('EXPR');
        $expressionBuilder->greaterThanOrEqual('taxon.left', 2)->willReturn('EXPR1');
        $expressionBuilder->lessThanOrEqual('taxon.right', 4)->willReturn('EXPR2');
        $expressionBuilder->equals('taxon.root', $rootTaxon)->willReturn('EXPR3');

        $dataSource->restrict('EXPR')->shouldBeCalled();

        $this->apply($dataSource, 'resource', ['mainTaxon' => 'category'], ['field' => 'taxon']);
    }
}
