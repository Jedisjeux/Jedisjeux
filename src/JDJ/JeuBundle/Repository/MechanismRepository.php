<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 21/12/2015
 * Time: 16:12
 */

namespace JDJ\JeuBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use JDJ\CoreBundle\Entity\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class MechanismRepository extends EntityRepository
{
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = array())
    {
        if (isset($criteria['query'])) {
            $queryBuilder
                ->andWhere($this->getAlias().'.name like :query')
                ->setParameter('query', '%'.$criteria['query'].'%');
            unset($criteria['query']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }

}