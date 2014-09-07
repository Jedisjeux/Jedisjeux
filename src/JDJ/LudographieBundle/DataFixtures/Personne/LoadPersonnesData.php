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
            image,
            slug
)
select      id,
            nom,
            prenom,
            siteweb,
            description,
            pays.id,
            photo,
            nom_clean
from        old_jedisjeux.jdj_personnes old
inner join  jdj_pays pays
                on pays.libelle = old.nationnalite
EOM;

    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
} 