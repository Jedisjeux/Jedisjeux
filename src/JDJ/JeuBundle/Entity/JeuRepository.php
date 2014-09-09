<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 28/06/2014
 * Time: 20:46
 */

namespace JDJ\JeuBundle\Entity;


use Doctrine\ORM\QueryBuilder;
use JDJ\WebBundle\Entity\EntityRepository;

class JeuRepository extends EntityRepository
{
    /**
     * @param QueryBuilder $queryBuilder
     *
     * @param array $criteria
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = null)
    {
        parent::applyCriteria($queryBuilder, $criteria);

        if (array_key_exists("personne", $criteria)) {
            $queryBuilder
                ->andWhere(":personneId MEMBER OF ".$this->getAlias().".auteurs
                    or :personneId MEMBER OF ".$this->getAlias().".illustrateurs
                    or :personneId MEMBER OF ".$this->getAlias().".editeurs
                ")
                ->setParameter("personneId", $criteria['personne']->getId());
            ;
        }
    }
} 