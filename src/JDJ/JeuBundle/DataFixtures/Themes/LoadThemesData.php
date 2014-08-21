<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/08/2014
 * Time: 20:02
 */

namespace JDJ\JeuBundle\DataFixtures\Themes;


use JDJ\JeuBundle\Entity\Theme;
use JDJ\WebBundle\DataFixtures\LoadEntityYMLData;

class LoadThemesData extends LoadEntityYMLData
{
    public function getYAMLFileName()
    {
        return __DIR__."/jdj_theme.yml";
    }

    public function getEntityNewInstance()
    {
        return new Theme();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
} 