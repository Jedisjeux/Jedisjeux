<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 28/06/2014
 * Time: 20:46
 */

namespace JDJ\JeuBundle\Entity;


use Doctrine\ORM\QueryBuilder;
use JDJ\CoreBundle\Entity\EntityRepository;

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
        if (array_key_exists('noteCount:moreThanOrEqual', $criteria)) {
            $queryBuilder
                ->andWhere("SIZE(".$this->getAlias().".notes) >= :noteCountMoreThanOrEqual")
                ->setParameter("noteCountMoreThanOrEqual", $criteria['noteCount:moreThanOrEqual'])
            ;
        }

        if (array_key_exists('noteAvg:moreThanOrEqual', $criteria)) {
            $queryBuilder
                ->join("o.notes", "jeuNote")
                ->join("jeuNote.note", "n")
                ->groupBy($this->getAlias().'.id')
                ->andHaving($queryBuilder->expr()->gte($queryBuilder->expr()->avg('n.valeur'), $criteria['noteAvg:moreThanOrEqual']))
                ->addSelect($queryBuilder->expr()->count('n.valeur'). "AS HIDDEN noteAvg")
            ;
        }
    }

    /**
     * Find jeu entities that match criteria "if you like this game"
     *
     * @param Jeu $jeu
     * @return \Pagerfanta\Pagerfanta
     */
    public function getIfYouLikeThisGame(Jeu $jeu)
    {
        $queryBuilder = $this->getCollectionQueryBuilder();

        /**
         * Preparing criteria handle with applyCriteria
         */

        $criteria = array(
            'noteAvg:moreThanOrEqual' => 7,
            'noteCount:moreThanOrEqual' => 3,
        );

        if (null !== $jeu->getAgeMin()) {
            $criteria = array_merge($criteria, array(
                'ageMin:moreThanOrEqual' => $jeu->getAgeMin()-2,
                "ageMin:lessThanOrEqual" => $jeu->getAgeMin()+2,
            ));
        }

        $this->applyCriteria($queryBuilder, $criteria);

        /**
         * Adding additionnal Criteria not handle with applyCriteria
         */

        /**
         * Filter on mechanisms
         */
        $mecanismesID = array();
        foreach($jeu->getMecanismes()->toArray() as $mecanisme) {
            $mecanismesID[] = $mecanisme->getId();
        }

        $queryBuilder
            ->join("o.mecanismes", "m")
            ->groupBy($this->getAlias().'.id')
            ->andWhere($queryBuilder->expr()->in("m.id", $mecanismesID))
            /**
             * having 2 (or more) identical mechanisms
             */
            ->andHaving($queryBuilder->expr()->gte($queryBuilder->expr()->count('m'), '2'))
            ->addSelect($queryBuilder->expr()->count('m'). "AS HIDDEN mecanismeCount")
        ;

        /**
         * Filter this game
         */
        $queryBuilder
            ->andWhere($this->getAlias().'.id <> :jeuId')
            ->setParameter("jeuId", $jeu->getId());


        $queryBuilder
            ->orderBy("mecanismeCount", 'desc')
            ->addOrderBy("noteAvg", 'desc');

        //$this->applySorting($queryBuilder, $orderBy);

        return $this->getPaginator($queryBuilder);
    }
} 