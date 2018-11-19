<?php

namespace spec\App\Calculator;

use App\Repository\ProductRepository;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

class ProductCountByTaxonCalculatorSpec extends ObjectBehavior
{
    function let(ProductRepository $productRepository)
    {
        $this->beConstructedWith($productRepository);
    }

    function it_calculates_product_count_for_a_taxon(
        ProductRepository $productRepository,
        TaxonInterface $taxon
    ): void {
        $productRepository->countByTaxon($taxon)->willReturn(3);

        $this->calculate($taxon)->shouldReturn(3);
    }
}
