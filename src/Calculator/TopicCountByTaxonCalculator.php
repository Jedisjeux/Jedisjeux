<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Calculator;

use App\Repository\TopicRepository;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicCountByTaxonCalculator
{
    /**
     * @var RepositoryInterface|TopicRepository
     */
    protected $topicRepository;

    public function __construct(RepositoryInterface $topicRepository)
    {
        $this->topicRepository = $topicRepository;
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
