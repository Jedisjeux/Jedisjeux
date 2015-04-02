<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 01/04/15
 * Time: 13:24
 */

namespace JDJ\CollectionBundle\DataFixtures\Collection;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadListeElementsData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
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
insert into jdj_list_element(collection_id, jeu_id)
select old.id_liste, old.id_element
from jedisjeux.jdj_liste_element old
inner join jdj_collection collection
			on collection.id = old.id_liste
inner join jdj_jeu jeu
			on jeu.id = old.id_element;
;

EOM;

        $this->getDatabaseConnection()->executeQuery($query);

    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 6;
    }
} 