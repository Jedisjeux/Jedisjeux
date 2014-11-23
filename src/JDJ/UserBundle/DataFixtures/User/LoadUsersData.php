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

class LoadUsersData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
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
select      old.user_id,
            old.username,
            old.user_email,
            old.group_id
from        old_jedisjeux.phpbb3_users old
group by    old.user_email
order by    old.user_id
limit       100
EOM;

        $oldUsers = $this->getDatabaseConnection()->fetchAll($query);

        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');

        foreach($oldUsers as $data) {

            $roles = array('ROLE_USER');
            switch($data['username']) {
                case 'loic_425':
                    $roles[] = 'ROLE_ADMIN';
                    break;
            }

            /** @var User $user */
            $user = $userManager->createUser();
            $user
                ->setEnabled(true)
                ->setUsername($data['username'])
                ->setPlainPassword($data['username'])
                ->setEmail($data['user_email'])
                ->setRoles($roles);

            $userManager->updateUser($user);

            $this->getDatabaseConnection()->update("fos_user", array(
                'id' => $data['user_id'],
            ), array(
                'id' => $user->getId(),
            ));
        }

        $roles = array("ROLE_USER", "ROLE_ADMIN");

        $userSeb = new User();
        $userSeb
            ->setEnabled(true)
            ->setUsername("sbienvenu")
            ->setPlainPassword("sbienvenu")
            ->setEmail("sbienvenu@gmail.com")
            ->setRoles($roles);

        $userManager->updateUser($userSeb);

    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
} 