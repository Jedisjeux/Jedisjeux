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

        /**
         * Filter on personne
         */
        if (array_key_exists("personne", (array)$criteria)) {
            $queryBuilder
                ->join($this->getAlias().".jeuNote", "jeuNote")
                ->join("jeuNote.jeu", "jeu")
                ->where(":personne MEMBER OF jeu.auteurs
                    or :personne MEMBER OF jeu.illustrateurs
                    or :personne MEMBER OF jeu.editeurs")
                ->setParameter("personne", $criteria['personne'])
            ;
            unset($criteria['personne']);
        }

        /**
         * Filter on author
         */
        if (array_key_exists("author", (array)$criteria)) {
            $queryBuilder
                ->join($this->getAlias().".jeuNote", "jeuNote")
                ->where(":authorId MEMBER OF jeuNote.author")
                ->setParameter("personneId", $criteria['author']->getId())
            ;
            unset($criteria['author']);
        }

        /**
         * filter on jeu
         */
        if (array_key_exists("jeu", (array)$criteria)) {
            $queryBuilder
                ->join($this->getAlias().".jeuNote", "jeuNote")
                ->andWhere('jeuNote.jeu = :jeu')
                ->setParameter("jeu", $criteria['jeu'])
            ;
            unset($criteria['jeu']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }
} 