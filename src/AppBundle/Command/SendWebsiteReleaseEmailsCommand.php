<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use AppBundle\Emails;
use AppBundle\Entity\Customer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Mailer\Sender\Sender;
use Sylius\Component\User\Security\TokenProvider;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SendWebsiteReleaseEmailsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:website-release-emails:send')
            ->setDescription('Send all emails about new website release.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command send all emails about new website release.
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));

        foreach ($this->getCustomerQueryBuilder()->getQuery()->iterate() as $row) {
            /** @var Customer $customer */
            $customer = $row[0];

            $user = $customer->getUser();

            $user->setConfirmationToken($this->getTokenProvider()->generateUniqueToken());
            $user->setPasswordRequestedAt(new \DateTime());

            $this->getManager()->flush();

            $output->writeln(sprintf('Sending email to <info>%s</info>', $customer->getEmail()));

            $this->getEmailSender()->send(Emails::WEBSITE_RELEASE, [$customer->getEmail()], [
                'customer' => $customer,
            ]);

            $this->getManager()->detach($customer);
        }
    }

    /**
     * @return TokenProvider
     */
    protected function getTokenProvider()
    {
        return $this->getContainer()->get('sylius.user.token_provider');
    }

    /**
     *
     * @return Sender
     */
    protected function getEmailSender()
    {
        return $this->getContainer()->get('sylius.email_sender');
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getCustomerQueryBuilder()
    {
        return $this->getCustomerRepository()->createQueryBuilder('o')
            ->select('o, user')
            ->join('o.user', 'user');
    }

    /**
     * @return EntityRepository
     */
    protected function getCustomerRepository()
    {
        return $this->getContainer()->get('sylius.repository.customer');
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
