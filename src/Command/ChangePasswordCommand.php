<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ChangePasswordCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sylius:user:change-password')
            ->setDescription('Change the password of a user.')
            ->setDefinition([
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
            ])
            ->setHelp(<<<EOT
The <info>sylius:user:change-password</info> command changes the password of a user:
  <info>php app/console sylius:user:change-password matthieu@email.com</info>
This interactive shell will first ask you for a password.
You can alternatively specify the password as a second argument:
  <info>php app/console fos:user:change-password matthieu@email.com mypassword</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        /** @var UserInterface $user */
        $user = $this->getUserRepository()->findOneByEmail($email);

        if (null === $user) {
            throw new \InvalidArgumentException(sprintf('Could not find user identified by email "%s"', $email));
        }

        $this->changePassword($user, $password);
        $this->getEntityManager()->flush();

        $output->writeln(sprintf('Change password user <comment>%s</comment>', $email));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('email')) {
            $email = $this->getHelper('question')->ask(
                $input,
                $output,
                new Question('Please enter an email:')
            );

            $input->setArgument('email', $email);
        }

        if (!$input->getArgument('password')) {
            $password = $this->getHelper('question')->ask(
                $input,
                $output,
                (new Question('Please choose a password:'))->setHidden(true)
            );

            $input->setArgument('password', $password);
        }
    }

    /**
     * @param UserInterface $user
     * @param string        $password
     */
    protected function changePassword(UserInterface $user, $password)
    {
        $user->setPlainPassword($password);
        $this->getContainer()->get('sylius.listener.password_updater')->updateUserPassword($user);
    }

    /**
     * @return ObjectManager|object
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('sylius.manager.app_user');
    }

    /**
     * @return UserRepositoryInterface|object
     */
    protected function getUserRepository()
    {
        return $this->getContainer()->get('sylius.repository.app_user');
    }
}
