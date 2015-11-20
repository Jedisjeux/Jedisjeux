<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 20/11/2015
 * Time: 13:37
 */

namespace JDJ\ComptaBundle\Command;

use Doctrine\ORM\EntityManager;
use JDJ\ComptaBundle\Entity\Bill;
use JDJ\ComptaBundle\Entity\Manager\SubscriptionManager;
use JDJ\ComptaBundle\Entity\Subscription;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SubscriptionsEndingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('compta:subscriptions:end')
            ->setDescription('Ending subscriptions with and ending date in the past');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Ending subscriptions");

        $subscriptions = $this
            ->getSubscriptionManager()
            ->getSubscriptionRepository()->findEndingSubscriptions()
            ->setCurrentPage(1)
            ->setMaxPerPage(10);

        $this->endSubscriptions($subscriptions);

        while ($subscriptions->getNbPages() > $subscriptions->getCurrentPage()) {
            $subscriptions->getNextPage();

            /** @var Subscription $subscription */
            foreach ($subscriptions as $subscription) {
                $subscription
                    ->setStatus(Subscription::FINISHED);

                $this->getEntityManager()->flush();
            }
        }


        $output->writeln("<comment>" . $subscriptions->getNbResults() . " subscriptions updated</comment>");
        $output->writeln("<info>Ending subscriptions OK</info>");
    }

    protected function endSubscriptions(Pagerfanta $subscriptions)
    {
        /** @var Subscription $subscription */
        foreach ($subscriptions as $subscription) {
            $subscription
                ->setStatus(Subscription::FINISHED);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return SubscriptionManager
     */
    protected function getSubscriptionManager()
    {
        return $this->getContainer()->get('app.manager.subscription');
    }
}