<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/08/2014
 * Time: 22:06
 */

namespace JDJ\JeuBundle\DataFixtures\Caracteristiques;


use JDJ\JeuBundle\Entity\Caracteristique;
use JDJ\WebBundle\DataFixtures\LoadEntityYMLData;

class LoadCaracteristiquesData extends LoadEntityYMLData
{
    public function getYAMLFileName()
    {
        return __DIR__."/jdj_caracteristique.yml";
    }

    public function getEntityNewInstance()
    {
        return new Caracteristique();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }

    public function getTableName()
    {
        return "jdj_caracteristique";
    }
} 