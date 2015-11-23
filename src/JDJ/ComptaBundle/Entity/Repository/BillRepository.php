<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 22/06/2015
 * Time: 20:50
 */

namespace JDJ\ComptaBundle\Entity\Repository;
use Doctrine\ORM\QueryBuilder;
use JDJ\CoreBundle\Entity\EntityRepository;


/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class BillRepository extends EntityRepository
{
    /**
     * @inheritdoc
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = null)
    {
        if (isset($criteria['query'])) {
            $queryBuilder
                ->join($this->getAlias().'.customer', 'customer')
                ->andWhere('customer.society like :query')
                ->setParameter('query', '%'.$criteria['query'].'%');
            unset($criteria['query']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }


    /**
     * @inheritdoc
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = null)
    {
        if (isset($sorting['society'])) {
            $queryBuilder
                ->join($this->getAlias().'.customer', 'customer')
                ->addOrderBy('customer.society', $sorting['society']);
            unset($sorting['society']);
        }

        parent::applySorting($queryBuilder, $sorting);
    }

}