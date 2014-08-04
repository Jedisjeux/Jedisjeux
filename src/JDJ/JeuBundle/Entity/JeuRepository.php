<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 28/06/2014
 * Time: 20:46
 */

namespace JDJ\JeuBundle\Entity;


use Doctrine\ORM\EntityRepository;

class JeuRepository extends EntityRepository
{
    /**
     * @param $personne_id
     * @return array
     */
    public function findAllByPersonne($personne_id)
    {
        $query = $this->_em
            ->createQuery('
                SELECT jeu
                FROM JDJJeuBundle:Jeu jeu
                LEFT JOIN jeu.auteurs auteur
                LEFT JOIN jeu.illustrateurs illustrateur
                LEFT JOIN jeu.editeurs editeur
                where auteur.id = :personne_id
                or illustrateur.id = :personne_id
                or editeur.id = :personne_id
            ')
            ->setParameter("personne_id", $personne_id)
        ;

        //echo $query->getSQL();

        return $query->getResult();
    }
} 