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

use App\Entity\Person;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductCountByPersonCalculator
{
    /**
     * @param Person $person
     *
     * @return int
     */
    public function calculateAsDesigner(Person $person)
    {
        $productCount = count($person->getDesignerProducts());

        return $productCount;
    }

    /**
     * @param Person $person
     *
     * @return int
     */
    public function calculateAsArtist(Person $person)
    {
        $productCount = count($person->getArtistProducts());

        return $productCount;
    }

    /**
     * @param Person $person
     *
     * @return int
     */
    public function calculateAsPublisher(Person $person)
    {
        $productCount = count($person->getPublisherProducts());

        return $productCount;
    }
}
