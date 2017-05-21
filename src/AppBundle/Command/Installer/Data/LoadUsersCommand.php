<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 30/12/2015
 * Time: 15:59
 */

namespace AppBundle\Command\Installer\Data;

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

        $batchSize = 20;
        $i = 0;

        foreach ($this->getUsers() as $data) {
            $output->writeln(sprintf("Loading <info>%s</info> user", $data['username']));
            $user = $this->createOrReplaceUser($data);
            $this->getEntityManager()->persist($user);

            if (($i % $batchSize) === 0) {
                $this->getEntityManager()->flush(); // Executes all updates.
                $this->getEntityManager()->clear(); // Detaches all objects from Doctrine!
            }

            ++$i;
        }

        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }


    protected function createOrReplaceUser($data)
    {
        $canonicalizer = $this->getContainer()->get('sylius.canonicalizer');

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

        $email = $data['email'];
        $emailCanonical = $canonicalizer->canonicalize($email);

        $user->getCustomer()->setCode('user-'.$data['id']);
        $user->getCustomer()->setEmail($email);
        $user->getCustomer()->setEmailCanonical($emailCanonical);
        $user->setEmail($email);
        $user->setEmailCanonical($emailCanonical);
        $user->setUsername($data['username']);
        $user->setUsernameCanonical($canonicalizer->canonicalize($user->getUsername()));
        $user->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $data['createdAt']));
        $user->setUpdatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $data['updatedAt']));

        if (null === $user->getId()) {
            $user->setPlainPassword(md5(uniqid($user->getUsername(), true)));
            $this->getContainer()->get('sylius.security.password_updater')->updatePassword($user);
        }

        $roles = array('ROLE_USER');
        switch($user->getUsername()) {
            // administrators only
            case 'loic_425':
            case 'jedisjeux':
            case 'kevetoile':
                $roles[] = 'ROLE_ADMIN';
                break;

            // administrators and publishers
            case 'Blue':
            case 'cyril83':
                $roles[] = 'ROLE_ADMIN';
                $roles[] = 'ROLE_PUBLISHER';
                break;

            // reviewers and redactors
            case 'allana':
            case 'bgarz':
                $roles[] = 'ROLE_REDACTOR';
                $roles[] = 'ROLE_REVIEWER';
                break;

            // reviewers only
            case 'sly078':
                $roles[] = 'ROLE_REVIEWER';
                break;

            // redactors only
            case 'Le Zeptien':
            case 'Krissou':
            case 'Arthelius':
            case 'chris06':
            case 'Evens':
            case 'nico':
            case 'vincelnx':
            case 'Wityender':
                $roles[] = 'ROLE_REDACTOR';
                break;
        }

        foreach ($user->getRoles() as $role) {
            $user->removeRole($role);
        }

        foreach ($roles as $role) {
            $user->addRole($role);
        }

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
            1 as enabled,
            FROM_UNIXTIME(old.user_regdate) as createdAt,
            FROM_UNIXTIME(old.user_regdate) as updatedAt
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
        return $this->getContainer()->get('sylius.factory.shop_user');
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
        return $this->getContainer()->get('sylius.repository.shop_user');
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}
