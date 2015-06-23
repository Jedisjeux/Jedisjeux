<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 22/06/2015
 * Time: 23:48
 */

namespace JDJ\ComptaBundle\Command;
use JDJ\ComptaBundle\Entity\BillProduct;
use JDJ\ComptaBundle\Entity\Manager\SubscriptionManager;
use JDJ\ComptaBundle\Entity\Subscription;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use JDJ\ComptaBundle\Entity\Bill;


/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class SubscriptionsImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('compta:subscriptions:create')
            ->setDescription('Create subscriptions from old jedisjeux')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Creating subscriptions from jedisjeux");

        $bills = $this->getBillRepository()->findAll();

        $createdItemCount = 0;
        $updatedItemCount = 0;

        /** @var Bill $bill */
        foreach($bills as $bill) {

            if (null === $bill->getPaidAt()) {
                continue;
            }

            /** @var BillProduct $billProduct */
            foreach ($bill->getBillProducts() as $billProduct) {
                $subscription = $this
                    ->getSubscriptionManager()
                    ->getSubscriptionRepository()
                    ->findOneBy(array(
                        'customer' => $billProduct->getBill()->getCustomer(),
                        'product' => $billProduct->getProduct(),
                    ));
                if (null === $subscription) {
                    $subscription = new Subscription();
                    $this->getEntityManager()->persist($subscription);
                    $createdItemCount ++;
                } else {
                    $updatedItemCount ++;
                }

                $subscription
                    ->setBill($bill)
                    ->setProduct($billProduct->getProduct())
                    ->setCreatedAt($bill->getPaidAt())
                    ->setStartAt($bill->getPaidAt())
                    ->setEndAt($this->getSubscriptionManager()->calculateEndingDate($subscription))
                    ->setCustomer($bill->getCustomer())
                    ->setToBeRenewed($subscription->getEndAt() < new \DateTime() ? false : true);

                $this->getEntityManager()->flush();
            }


        }

        $output->writeln("<comment>" . $createdItemCount . " items created</comment>");
        $output->writeln("<comment>" . $updatedItemCount . " items updated</comment>");
        $output->writeln("<info>Creating subscriptions OK</info>");
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getBillRepository()
    {
        return $this->getEntityManager()->getRepository('JDJComptaBundle:Bill');
    }

    /**
     * @return SubscriptionManager
     */
    protected function getSubscriptionManager()
    {
        return $this->getContainer()->get('app.manager.subscription');
    }
}