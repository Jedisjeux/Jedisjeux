<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 15/06/2015
 * Time: 21:34
 */

namespace JDJ\ComptaBundle\Entity\Repository;
use Doctrine\ORM\QueryBuilder;
use JDJ\CoreBundle\Entity\EntityRepository;


/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class CustomerRepository extends EntityRepository
{
    /**
     * @inheritdoc
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = array())
    {
        if (isset($criteria['query'])) {
            $queryBuilder
                ->andWhere($this->getAlias().'.society like :query or '.$this->getAlias().'.email like :query or '.$this->getAlias().'.id like :query')
                ->setParameter('query', '%'.$criteria['query'].'%');
            unset($criteria['query']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }

}