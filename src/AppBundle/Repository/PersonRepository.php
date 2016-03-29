<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/12/2015
 * Time: 14:11
 */

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use JDJ\CoreBundle\Entity\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PersonRepository extends EntityRepository
{
    /**
     * @inheritdoc
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = array())
    {
        if (isset($criteria['query'])) {
            $queryBuilder
                ->andWhere($this->getAlias().'.slug like :query')
                ->setParameter('query', '%'.$criteria['query'].'%');
            unset($criteria['query']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }

    /**
     * @inheritdoc
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = array())
    {
        if (isset($sorting['gameCount'])) {
            $queryBuilder->addSelect($queryBuilder->expr()->sum(
                    "SIZE(".$this->getAlias().".designerProducts)",
                    "SIZE(".$this->getAlias().".publisherProducts)",
                    "SIZE(".$this->getAlias().".artistProducts)").
                " as HIDDEN gameCount");
            $queryBuilder->addOrderBy("gameCount", $sorting['gameCount']);
            unset($sorting['gameCount']);
        }

        parent::applySorting($queryBuilder, $sorting);
    }


}