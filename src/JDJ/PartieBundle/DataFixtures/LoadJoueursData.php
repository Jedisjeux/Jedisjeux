<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 03/03/15
 * Time: 19:03
 */

namespace JDJ\PartieBundle\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JDJ\CoreBundle\Entity\Image;
use JDJ\JeuBundle\Entity\Jeu;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadJoueursData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

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
        $this->manager = $manager;

        $query = <<<EOM
insert into jdj_joueur (partie_id, nom, score)
select      old.partie_id, old.joueur, old.score
from        jedisjeux.jdj_scores old
inner join  jdj_partie partie
                on partie.id = old.partie_id
EOM;

        $this->getDatabaseConnection()->executeQuery($query);

    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 5;
    }
} 