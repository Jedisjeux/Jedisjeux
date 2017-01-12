<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Updater;

use AppBundle\Calculator\TopicCountByTaxonCalculator;
use AppBundle\Entity\Taxon;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicCountByTaxonUpdater
{
    /**
     * @var TopicCountByTaxonCalculator
     */
    protected $calculator;

    /**
     * TopicCountByTaxonSubscriber constructor.
     *
     * @param TopicCountByTaxonCalculator $calculator
     */
    public function __construct(TopicCountByTaxonCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function update(Taxon $taxon)
    {
        $topicCount = $this->calculator->calculate($taxon);
        $taxon->setTopicCount($topicCount);
    }
}
