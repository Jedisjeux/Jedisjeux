<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/08/2014
 * Time: 20:02
 */

namespace JDJ\JeuBundle\DataFixtures\Mecanismes;


use JDJ\JeuBundle\Entity\Mecanisme;
use JDJ\WebBundle\DataFixtures\LoadEntityYMLData;

class LoadMecanismesData extends LoadEntityYMLData
{
    public function getYAMLFileName()
    {
        return __DIR__."/jdj_mecanisme.yml";
    }

    public function getEntityNewInstance()
    {
        return new Mecanisme();
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
        return "jdj_mecanisme";
    }
} 