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
     * ProductCountByTaxonUpdater constructor.
     */
    public function __construct(protected ProductCountByTaxonCalculator $calculator)
    {
    }

    public function update(Taxon $taxon): void
    {
        $productCount = $this->calculator->calculate($taxon);
        $taxon->setProductCount($productCount);
    }
}
