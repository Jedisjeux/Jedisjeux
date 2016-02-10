<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 28/06/2014
 * Time: 20:46
 */

namespace JDJ\JeuBundle\Repository;


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
        if (isset($criteria['query'])) {
            $queryBuilder
                ->andWhere($this->getAlias().'.name like :query')
                ->setParameter('query', '%'.$criteria['query'].'%');
            unset($criteria['query']);
        }

        if (isset($criteria['mechanism'])) {
            $this->joinTo($queryBuilder, 'mechanisms', 'mechanism');
            $queryBuilder
                ->andWhere('mechanism = :mechanism')
                ->setParameter('mechanism', $criteria['mechanism']);
            unset($criteria['mechanism']);
        }

        if (isset($criteria['theme'])) {
            $this->joinTo($queryBuilder, 'themes', 'theme');
            $queryBuilder
                ->andWhere('theme.id = :theme')
                ->setParameter('theme', $criteria['theme']);
            unset($criteria['theme']);
        }

        if (array_key_exists("personne", $criteria)) {
            $queryBuilder
                ->andWhere(":personneId MEMBER OF ".$this->getAlias().".auteurs
                    or :personneId MEMBER OF ".$this->getAlias().".illustrateurs
                    or :personneId MEMBER OF ".$this->getAlias().".editeurs
                ")
                ->setParameter("personneId", $criteria['personne']->getId());
            ;
            unset($criteria['personne']);
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
                ->addSelect($queryBuilder->expr()->avg('n.valeur'). "AS HIDDEN noteAvg")
            ;
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }

    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = array())
    {
        if (array_key_exists('average', $sorting)) {
            $queryBuilder
                ->leftJoin($this->getAlias().'.notes', "jeuNote")
                ->leftJoin("jeuNote.note", "n")
                ->groupBy($this->getAlias().'.id')
                ->addSelect($queryBuilder->expr()->avg('n.valeur'). "AS HIDDEN noteAvg")
                ->addOrderBy('noteAvg', $sorting['average']);

            unset($sorting['average']);
        }

        parent::applySorting($queryBuilder, $sorting);
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
        foreach($jeu->getMechanisms()->toArray() as $mecanisme) {
            $mecanismesID[] = $mecanisme->getId();
        }

        $queryBuilder
            ->join("o.mechanisms", "m")
            ->groupBy($this->getAlias().'.id')
            /**
             * having 2 (or more) identical mechanisms
             */
            ->andHaving($queryBuilder->expr()->gte($queryBuilder->expr()->count('m'), '2'))
            ->addSelect($queryBuilder->expr()->count('m'). "AS HIDDEN mecanismeCount")
        ;
        if (count($mecanismesID) > 0) {
            $queryBuilder->andWhere($queryBuilder->expr()->in("m.id", $mecanismesID));
        } else {
            /**
             * Therefore, we can't have good game advices...
             */
            $queryBuilder->andWhere($queryBuilder->expr()->in("m.id", array(0)));
        }

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

    public function advancedSearch(array $criteria = null)
    {

        $queryBuilder = $this->getCollectionQueryBuilder();

        /**
         * Handle criteria with another method than generic
         */
        $mechanisms = null;

        if (isset($criteria['mechanism'])) {
            $mechanisms = $criteria['mechanism'];
            unset($criteria['mechanism']);
        }

        if (array_key_exists('ageMin', $criteria) and null !== $criteria['ageMin']) {
            $ageMin = $criteria['ageMin'];
            $criteria['ageMin:lessThanOrEqual'] = $ageMin;
        }
        unset($criteria['ageMin']);

        if (isset($criteria['theme'])) {
            $criteria['theme'] = array($criteria['theme']);
        } else {
            unset($criteria['theme']);
        }



        $this->applyCriteria($queryBuilder, $criteria);

        if (null !== ($mechanisms)) {

            $mecanismesID = array();
            foreach($mechanisms as $key => $mechanism) {
                $queryBuilder
                    ->join("o.mechanisms", "mechanism".$key);

                $mecanismesID[] = $mechanism->getId();
                $queryBuilder
                    ->andWhere("mechanism".$key.".id = :mecanismeId".$key)
                    ->setParameter("mecanismeId".$key, $mechanism->getId());
            }

        }

        if (isset($criteria['joueurCount'])) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->lte($this->getPropertyName("joueurMin"), $criteria['joueurCount'])
            );
            $queryBuilder->andWhere(
                $queryBuilder->expr()->gte($this->getPropertyName("joueurMax"), $criteria['joueurCount'])
                .' or '.$this->getPropertyName("joueurMax"). ' is null'
            );
        }


        return $this->getPaginator($queryBuilder);
    }
} 