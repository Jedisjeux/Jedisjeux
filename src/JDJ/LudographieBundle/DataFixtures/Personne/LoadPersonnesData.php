<?php
/**
 * Created by PhpStorm.
 * User: robert & paulette
 * Date: 07/09/14
 * Time: 17:30
 */

namespace JDJ\LudographieBundle\DataFixtures\Personne;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadPersonnesData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
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
insert into jdj_personne (
            id,
            nom,
            prenom,
            siteWeb,
            description,
            pays_id,
            slug
)
select      old.id,
            old.nom_famille,
            old.prenom,
            old.siteweb,
            old.description,
            country.id,
            replace(old.nom_clean, ' ', '-') as slug
from        jedisjeux.jdj_personnes old
left join   jdj_pays country
                on CONVERT(country.libelle USING utf8) = CONVERT(old.nationnalite USING utf8)
where       old.id <> 14
EOM;

        $this->getDatabaseConnection()->executeQuery($query);

    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
} 