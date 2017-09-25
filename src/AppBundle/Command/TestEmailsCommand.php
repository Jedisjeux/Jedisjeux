<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use AppBundle\Emails;
use AppBundle\Entity\Customer;
use Sylius\Bundle\UserBundle\Mailer\Emails as SyliusEmails;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Sylius\Bundle\UserBundle\UserEvents;
use Sylius\Component\Mailer\Sender\Sender;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TestEmailsCommand extends ContainerAwareCommand
{
    /**
     * @var Generator
     */
    protected $faker;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->faker = FakerFactory::create();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:emails:test')
            ->setDescription('Tests all emails.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command tests all emails.
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));
        $output->writeln(sprintf('Envoi de l\'email <info>%s</info>', Emails::USER_REGISTRATION));
        $this->sendRegistrationEmail();
        $output->writeln(sprintf('Envoi de l\'email <info>%s</info>', SyliusEmails::RESET_PASSWORD_TOKEN));
        $this->sendResetPasswordTokenEmail();
        $output->writeln(sprintf('Envoi de l\'email <info>%s</info>', Emails::WEBSITE_RELEASE));
        $this->sendWebsiteReleaseEmail();
    }

    protected function sendRegistrationEmail()
    {
        $customer = $this->createCustomer();
        $this->getEventDispatcher()->dispatch('sylius.customer.post_register', new GenericEvent($customer));
    }

    protected function sendResetPasswordTokenEmail()
    {
        $user = $this->createCustomer()->getUser();
        $user->setPasswordResetToken($this->faker->creditCardNumber);
        $this->getEventDispatcher()->dispatch(UserEvents::REQUEST_RESET_PASSWORD_TOKEN, new GenericEvent($user));
    }

    protected function sendWebsiteReleaseEmail()
    {
        $customer = $this->createCustomer();
        $customer->getUser()->setPasswordResetToken($this->faker->creditCardNumber);
        $this->getEmailSender()->send(Emails::WEBSITE_RELEASE, [$customer->getEmail()], [
            'customer' => $customer,
        ]);
    }

    /**
     * @return Customer
     */
    protected function createCustomer()
    {
        /** @var UserInterface $user */
        $user = $this->getContainer()->get('sylius.factory.app_user')->createNew();
        $user->setUsername($this->faker->userName);
        $user->setPlainPassword($this->faker->password());

        /** @var Customer $customer */
        $customer = $this->getContainer()->get('sylius.factory.customer')->createNew();
        $customer->setUser($user);

        $customer->setEmail($this->faker->email);
        $customer->setFirstName($this->faker->firstName);
        $customer->setLastName($this->faker->lastName);

        return $customer;
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
     * @return EventDispatcher
     */
    protected function getEventDispatcher()
    {
        return $this->getContainer()->get('event_dispatcher');
    }
}
