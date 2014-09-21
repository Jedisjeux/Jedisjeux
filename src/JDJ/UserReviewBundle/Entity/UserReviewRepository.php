<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 10/09/2014
 * Time: 20:51
 */

namespace JDJ\UserReviewBundle\Entity;


use Doctrine\ORM\QueryBuilder;
use JDJ\CoreBundle\Entity\EntityRepository;

class UserReviewRepository extends EntityRepository
{
    /**
     * @param QueryBuilder $queryBuilder
     *
     * @param array $criteria
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = null)
    {
        parent::applyCriteria($queryBuilder, $criteria);

        /**
         * Filter on personne
         */
        if (array_key_exists("personne", (array)$criteria)) {
            $queryBuilder
                ->join($this->getAlias().".jeuNote", "jeuNote")
                ->join("jeuNote.jeu", "jeu")
                ->where(":personneId MEMBER OF jeu.auteurs
                    or :personneId MEMBER OF jeu.illustrateurs
                    or :personneId MEMBER OF jeu.editeurs")
                ->setParameter("personneId", $criteria['personne']->getId())
            ;
        }

        /**
         * filter on jeu
         */
        if (array_key_exists("jeu", (array)$criteria)) {
            $queryBuilder
                ->join($this->getAlias().".jeuNote", "jeuNote")
                ->andWhere($queryBuilder->expr()->eq("jeuNote.jeu", ':jeuId'))
                ->setParameter("jeuId", $criteria['jeu']->getId())
            ;
        }
    }
} 