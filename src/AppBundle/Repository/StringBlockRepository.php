<?php

/*
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use Doctrine\ODM\PHPCR\Query\Builder\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\DocumentRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class StringBlockRepository extends DocumentRepository
{
    /**
     * {@inheritdoc}
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = [])
    {
        if (isset($criteria['query'])) {
            $queryBuilder
                ->andWhere()->like()->field($this->getPropertyName('body'))->literal('%' . $criteria['query'] . '%');

            unset($criteria['query']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }
}
