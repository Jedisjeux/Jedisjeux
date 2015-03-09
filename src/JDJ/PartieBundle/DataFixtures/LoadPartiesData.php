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

class LoadPartiesData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
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
insert into jdj_partie (id, jeu_id, author_id, playedAt)
select      old.partie_id, old.game_id, old.user_id, old.date
from        jedisjeux.jdj_parties old
inner join  jdj_jeu j
                on j.id = old.game_id
inner join   fos_user user
                on user.id = old.user_id
EOM;

        $this->getDatabaseConnection()->executeQuery($query);

        $query = <<<EOM
insert into jdj_user_partie (partie_id, user_id)
select      distinct old.partie_id, old.user_id
from        jedisjeux.jdj_parties_liees old
inner join  jdj_partie partie
                on partie.id = old.partie_id
inner join   fos_user user
                on user.id = old.user_id
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