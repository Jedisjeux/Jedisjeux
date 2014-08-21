<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/08/2014
 * Time: 22:36
 */

namespace JDJ\JeuBundle\DataFixtures\Addon;


use JDJ\JeuBundle\Entity\TypeAddon;
use JDJ\WebBundle\DataFixtures\LoadEntityYMLData;

class LoadTypeAddonData extends LoadEntityYMLData
{
    public function getYAMLFileName()
    {
        return __DIR__."/jdj_type_addon.yml";
    }

    public function getEntityNewInstance()
    {
        return new TypeAddon();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
} 