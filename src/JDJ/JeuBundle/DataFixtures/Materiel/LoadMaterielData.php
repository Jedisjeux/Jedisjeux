<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/08/2014
 * Time: 22:23
 */

namespace JDJ\JeuBundle\DataFixtures\Materiel;


use JDJ\JeuBundle\Entity\Materiel;
use JDJ\WebBundle\DataFixtures\LoadEntityYMLData;

class LoadMaterielData extends LoadEntityYMLData
{
    public function getYAMLFileName()
    {
        return __DIR__."/jdj_materiel.yml";
    }

    public function getEntityNewInstance()
    {
        return new Materiel();
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
        return "jdj_materiel";
    }
} 