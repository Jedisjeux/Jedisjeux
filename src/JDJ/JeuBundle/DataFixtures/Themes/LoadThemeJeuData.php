<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 04/09/2014
 * Time: 00:39
 */

namespace JDJ\JeuBundle\DataFixtures\Mechanisms;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadThemeJeuData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnection()
    {
        return $this->container->get('database_connection');
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $dbalConnection = $this->getDatabaseConnection();
        $query = <<<EOM
        insert into jdj_theme_jeu (
                    jeu_id, theme_id
        )
        select      jeux_id, theme_id
        from        jedisjeux.jdj_themelieur ml
        inner join  jdj_jeu jeu
                        on jeu.id = ml.jeux_id
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 4;
    }
} 