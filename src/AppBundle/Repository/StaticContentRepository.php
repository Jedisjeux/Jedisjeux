<?php

/*
 * This file is part of Alceane.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use Doctrine\ODM\PHPCR\Query\Builder\QueryBuilder;
use Sylius\Bundle\ContentBundle\Doctrine\ODM\PHPCR\StaticContentRepository as BaseStaticContentRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class StaticContentRepository extends BaseStaticContentRepository
{
    /**
     * {@inheritdoc}
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = [])
    {
        if (isset($criteria['query'])) {
            $queryBuilder
                ->andWhere()->like()->field($this->getPropertyName('title'))->literal('%' . $criteria['query'] . '%');

            unset($criteria['query']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }
}
