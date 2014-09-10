<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 10/09/2014
 * Time: 19:31
 */

namespace JDJ\UserReviewBundle\DataFixtures\UserReview;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadUserReviewsData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
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
insert into jdj_user_review (
            id,
            libelle,
            body,
            createdAt,
            jeuNote_id
)
select      old.id,
            old.accroche,
            concat ('<p>', old.avis, '</p>'),
            date,
            jeuNote.id
from        old_jedisjeux.jdj_avis old
inner join  jdj_jeu_note jeuNote
                on jeuNote.id = old.id
where       old.avis <> ''
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