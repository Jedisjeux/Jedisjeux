<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/08/2014
 * Time: 20:02
 */

namespace JDJ\JeuBundle\DataFixtures\Mechanisms;


use JDJ\JeuBundle\Entity\Mechanism;
use JDJ\WebBundle\DataFixtures\LoadEntityYMLData;

class LoadMechanismsData extends LoadEntityYMLData
{
    public function getYAMLFileName()
    {
        return __DIR__."/jdj_mechanism.yml";
    }

    public function getEntityNewInstance()
    {
        return new Mechanism();
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
        return "jdj_mechanism";
    }
} 