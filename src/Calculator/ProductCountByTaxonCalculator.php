<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Calculator;

use App\Repository\ProductRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductCountByTaxonCalculator
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * TopicCountByTaxonCalculator constructor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param TaxonInterface $taxon
     *
     * @return int
     */
    public function calculate(TaxonInterface $taxon)
    {
        $productCount = $this->productRepository->countByTaxon($taxon);

        return $productCount;
    }
}
