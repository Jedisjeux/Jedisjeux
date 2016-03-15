<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 30/12/2015
 * Time: 15:59
 */

namespace AppBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class LoadUsersCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:users:load')
            ->setDescription('Load users');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getUsers() as $data) {
            $output->writeln(sprintf("Loading <info>%s</info> user", $data['username']));
            $user = $this->createOrReplaceUser($data);
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
            $this->getEntityManager()->clear();
        }
    }


    protected function createOrReplaceUser($data)
    {
        $canonicalizer = $this->getContainer()->get('sylius.user.canonicalizer');

        /*
         * @var UserInterface
         * @var $customer CustomerInterface
         */
        $user = $this->getUserRepository()->findOneByEmail($data['email']);

        if (null === $user) {
            $user = $this->getUserFactory()->createNew();
            $customer = $this->getCustomerFactory()->createNew();
            $user->setCustomer($customer);
        }

        $user->getCustomer()->setCode('user-'.$data['id']);
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);
        $user->setUsernameCanonical($canonicalizer->canonicalize($user->getUsername()));
        $user->setEmailCanonical($canonicalizer->canonicalize($user->getEmail()));

        if (null === $user->getId()) {
            $user->setPlainPassword(md5(uniqid($user->getUsername(), true)));
            $this->getContainer()->get('sylius.user.password_updater')->updatePassword($user);
        }

        $roles = array('ROLE_USER');
        switch($user->getUsername()) {
            case 'loic_425':
                $roles[] = 'ROLE_ADMIN';
                break;
            case 'jedisjeux':
                $roles[] = 'ROLE_ADMIN';
                break;
        }

        $user->setRoles($roles);
        $user->setEnabled(true);

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function getUsers()
    {
        // TODO find field for enabled property
        $query = <<<EOM
select      old.user_id as id,
            old.username as username,
            old.user_email as email,
            old.user_avatar,
            old.group_id,
            1 as enabled
from        jedisjeux.phpbb3_users old
where       old.user_email != ''
group by    old.username_clean
order by    old.user_id asc
EOM;
        $rows = $this->getDatabaseConnection()->fetchAll($query);

        return $rows;
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return FactoryInterface
     */
    protected function getUserFactory()
    {
        return $this->getContainer()->get('sylius.factory.user');
    }

    /**
     * @return FactoryInterface
     */
    protected function getCustomerFactory()
    {
        return $this->getContainer()->get('sylius.factory.customer');
    }

    /**
     * @return UserRepositoryInterface
     */
    protected function getUserRepository()
    {
        return $this->getContainer()->get('sylius.repository.user');
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}
