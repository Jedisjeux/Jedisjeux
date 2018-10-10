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

use App\Calculator\ProductCountByPersonCalculator;
use App\Entity\Person;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductCountByPersonUpdater
{
    /**
     * @var ProductCountByPersonCalculator
     */
    protected $calculator;

    /**
     * ProductCountByTaxonUpdater constructor.
     *
     * @param ProductCountByPersonCalculator $calculator
     */
    public function __construct(ProductCountByPersonCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @param Person $person
     */
    public function update(Person $person)
    {
        $productCountAsDesigner = $this->calculator->calculateAsDesigner($person);
        $productCountAsArtist = $this->calculator->calculateAsArtist($person);
        $productCountAsPublisher = $this->calculator->calculateAsPublisher($person);

        $person->setProductCountAsDesigner($productCountAsDesigner);
        $person->setProductCountAsArtist($productCountAsArtist);
        $person->setProductCountAsPublisher($productCountAsPublisher);

        $person->setProductCount($productCountAsDesigner + $productCountAsArtist + $productCountAsPublisher);
    }
}
