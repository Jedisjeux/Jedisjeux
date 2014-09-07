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
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadUsersData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');

        /** @var User $loic */
        $loic = $userManager->createUser();
        $loic
            ->setEnabled(true)
            ->setUsername("loic_425")
            ->setPlainPassword("loic_425")
            ->setEmail("lc.fremont@gmail.com")
            ->setRoles(array('ROLE_ADMIN'));

        $userManager->updateUser($loic);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
} 