<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/08/2014
 * Time: 20:02
 */

namespace JDJ\CoreBundle\DataFixtures\Cible;


use JDJ\CoreBundle\Entity\Cible;
use JDJ\WebBundle\DataFixtures\LoadEntityYMLData;

class LoadCiblesData extends LoadEntityYMLData
{
    public function getYAMLFileName()
    {
        return __DIR__."/jdj_cible.yml";
    }

    public function getEntityNewInstance()
    {
        return new Cible();
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
        return "jdj_cible";
    }
} 