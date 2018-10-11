<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Updater;

use App\Calculator\ProductCountByTaxonCalculator;
use App\Entity\Taxon;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductCountByTaxonUpdater
{
    /**
     * @var ProductCountByTaxonCalculator
     */
    protected $calculator;

    /**
     * ProductCountByTaxonUpdater constructor.
     *
     * @param ProductCountByTaxonCalculator $calculator
     */
    public function __construct(ProductCountByTaxonCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function update(Taxon $taxon)
    {
        $productCount = $this->calculator->calculate($taxon);
        $taxon->setProductCount($productCount);
    }
}
