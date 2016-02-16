<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 16/02/2016
 * Time: 14:04
 */

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use JDJ\CoreBundle\Entity\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GameReviewRepository extends EntityRepository
{
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = array())
    {
        // TODO see why it doesn't work
        //$this->joinTo($queryBuilder, $this->getAlias().'.rate', 'rate');

        $queryBuilder
            ->join($this->getAlias().'.rate', 'rate');

        if (isset($criteria['game'])) {
            $queryBuilder
                ->andWhere('rate.game = :game')
                ->setParameter('game', $criteria['game']);
            unset($criteria['game']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }

}