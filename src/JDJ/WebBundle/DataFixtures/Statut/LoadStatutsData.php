<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 09/09/2014
 * Time: 19:15
 */

namespace JDJ\WebBundle\DataFixtures\Statut;


use JDJ\WebBundle\DataFixtures\LoadEntityYMLData;
use JDJ\WebBundle\Entity\Statut;

/**
 * Class LoadStatutsData
 * @package JDJ\WebBundle\DataFixtures\Statut
 */
class LoadStatutsData extends LoadEntityYMLData
{
    public function getYAMLFileName()
    {
        return __DIR__."/jdj_statuts.yml";
    }

    public function getEntityNewInstance()
    {
        return new Statut();
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
        return "jdj_statut";
    }
} 