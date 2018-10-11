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

use App\Repository\TopicRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicCountByTaxonCalculator
{
    /**
     * @var TopicRepository
     */
    protected $topicRepository;

    /**
     * TopicCountByTaxonCalculator constructor.
     *
     * @param TopicRepository $topicRepository
     */
    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    /**
     * @param TaxonInterface $taxon
     *
     * @return int
     */
    public function calculate(TaxonInterface $taxon)
    {
        $topicCount = $this->topicRepository->countByTaxon($taxon);

        return $topicCount;
    }
}
