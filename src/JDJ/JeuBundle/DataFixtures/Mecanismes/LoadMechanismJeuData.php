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

class LoadMechanismJeuData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
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
        $query = <<<EOM
        insert into jdj_mechanism_jeu (
                    jeu_id, mecanisme_id
        )
        select      jeux_id, mecanisme_id
        from        old_jedisjeux.jdj_mecanismelieur ml
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