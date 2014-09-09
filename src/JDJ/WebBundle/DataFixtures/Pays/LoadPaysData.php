<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/08/2014
 * Time: 22:26
 */

namespace JDJ\WebBundle\DataFixtures\Pays;


use JDJ\WebBundle\DataFixtures\LoadEntityYMLData;
use JDJ\WebBundle\Entity\Pays;

/**
 * Class LoadPaysData
 * @package JDJ\WebBundle\DataFixtures\Pays
 */
class LoadPaysData extends LoadEntityYMLData
{
    public function getYAMLFileName()
    {
        return __DIR__."/jdj_pays.yml";
    }

    public function getEntityNewInstance()
    {
        return new Pays();
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
        return "jdj_pays";
    }
} 