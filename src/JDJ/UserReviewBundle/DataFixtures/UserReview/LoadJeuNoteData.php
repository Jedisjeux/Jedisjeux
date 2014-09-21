<?php
/**
 * Created by PhpStorm.
 * User: robert & paulette
 * Date: 07/09/14
 * Time: 18:07
 */

namespace JDJ\UserReviewBundle\DataFixtures\UserReview;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadJeuNoteData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
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
insert into jdj_jeu_note (
            id,
            author_id,
            jeu_id,
            note_id,
            createdAt,
            updatedAt
)
select      old.id,
            user.id,
            jeu.id,
            note.id,
            old.date,
            null
from        old_jedisjeux.jdj_avis old
inner join  jdj_jeu jeu
                on jeu.id = old.game_id
inner join  fos_user user
                on user.id = old.user_id
inner join  jdj_note note
                on note.id = old.note
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