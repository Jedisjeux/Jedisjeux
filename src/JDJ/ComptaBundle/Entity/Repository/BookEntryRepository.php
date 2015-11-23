<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 05/06/2015
 * Time: 10:22
 */

namespace JDJ\ComptaBundle\Entity\Repository;


use Doctrine\ORM\QueryBuilder;
use JDJ\CoreBundle\Entity\EntityRepository;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class BookEntryRepository extends EntityRepository
{
    /**
     * @inheritdoc
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = null)
    {
        if (isset($criteria['query'])) {
            $queryBuilder
                ->andWhere($this->getAlias().'.label like :query or '.$this->getAlias().'.price like :query')
                ->setParameter('query', '%'.$criteria['query'].'%');
            unset($criteria['query']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }
}