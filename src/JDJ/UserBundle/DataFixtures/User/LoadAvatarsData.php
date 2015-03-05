<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 03/09/2014
 * Time: 21:26
 */

namespace JDJ\UserBundle\DataFixtures\User;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Elastica\Transport\Null;
use JDJ\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadAvatarsData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
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
insert into jdj_avatar (path)
select      old.user_avatar
from        jedisjeux.phpbb3_users old
inner join  fos_user user
                on user.id = old.user_id
where       old.user_avatar <> ''
EOM;

        $this->getDatabaseConnection()->executeQuery($query);


        $query = <<<EOM
update fos_user user
inner join jedisjeux.phpbb3_users old
                on old.user_id = user.id
inner join  jdj_avatar avatar
                on avatar.path = old.user_avatar
set user.avatar_id = avatar.id
where       old.user_avatar <> ''
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