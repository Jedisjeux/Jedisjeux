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

use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicCountByTaxonCalculator
{
    public function __construct(private RepositoryInterface $topicRepository)
    {
    }

    /**
     * @return int
     */
    public function calculate(TaxonInterface $taxon)
    {
        $topicCount = $this->topicRepository->countByTaxon($taxon);

        return $topicCount;
    }
}
