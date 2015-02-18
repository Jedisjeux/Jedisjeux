<?php
/**
 * Created by PhpStorm.
 * User: robert & paulette
 * Date: 07/09/14
 * Time: 17:50
 */

namespace JDJ\LudographieBundle\DataFixtures\Personne;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadEditeurJeuData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
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
insert into jdj_editeur_jeu (
            personne_id,
            jeu_id
)
select      personne.id,
            jeu.id
from        jedisjeux.jdj_personne_game old
inner join  jdj_jeu jeu
                on jeu.id = old.id_game
inner join  jdj_personne personne
                on personne.id = old.id_personne
where       old.type_relation = 'editeur'
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