<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 01/04/15
 * Time: 13:11
 */

namespace JDJ\CollectionBundle\DataFixtures\Collection;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadCollectionsData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
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
insert into jdj_collection(id, name, user_id, description, slug)
SELECT old.id_liste, old.nom, old.id_user, old.description, 'test'
-- , dateliste TODO adding createdAt
FROM jedisjeux.jdj_liste old
inner join fos_user user
				on user.id = old.id_user
where id_type = 4 -- liste de jeux
and id_user <> 569 -- jedisjeux
;

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