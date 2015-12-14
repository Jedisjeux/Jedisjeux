<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 14/12/2015
 * Time: 10:03
 */

namespace JDJ\ComptaBundle\Entity\Repository;

use Doctrine\ORM\QueryBuilder;
use JDJ\CoreBundle\Entity\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerRepository extends EntityRepository
{
    /**
     * @inheritdoc
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = null)
    {
        if (isset($criteria['query'])) {
            $queryBuilder
                ->andWhere($this->getAlias().'.name like :query or '.$this->getAlias().'.id like :query')
                ->setParameter('query', '%'.$criteria['query'].'%');
            unset($criteria['query']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }
}