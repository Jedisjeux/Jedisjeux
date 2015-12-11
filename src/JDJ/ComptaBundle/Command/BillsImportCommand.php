<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 16/06/2015
 * Time: 19:33
 */

namespace JDJ\ComptaBundle\Command;

use JDJ\ComptaBundle\Entity\BillProduct;
use JDJ\ComptaBundle\Entity\Customer;
use JDJ\ComptaBundle\Entity\Manager\BillManager;
use JDJ\ComptaBundle\Entity\PaymentMethod;
use JDJ\ComptaBundle\Entity\Repository\AddressRepository;
use JDJ\ComptaBundle\Entity\Repository\ProductRepository;
use JDJ\CoreBundle\Entity\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JDJ\ComptaBundle\Entity\Product;
use JDJ\ComptaBundle\Entity\Bill;


/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class BillsImportCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('compta:bills:import')
            ->setDescription('Import bills from old jedisjeux')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Importing bills from old jedisjeux");
        $query = <<<EOM
select      facture.id,
            facture.idModeReglement,
            facture.idClient,
            facture.dateCreation,
            facture.datePaiement
from        zf_jedisjeux_test.cpta_facture facture
order by    facture.id
EOM;

        $oldItems = $this->getDatabaseConnection()->fetchAll($query);

        $createdItemCount = 0;
        $updatedItemCount = 0;

        foreach($oldItems as $data) {

            /** @var Bill $bill */
            $bill = $this
                ->getBillManager()
                ->getBillRepository()
                ->find($data['id']);
            if (null === $bill) {
                $bill = new Bill();
                $this->getEntityManager()->persist($bill);
                $createdItemCount ++;
            } else {
                $updatedItemCount ++;
            }

            /** @var Customer $customer */
            $customer = $this->getCustomerRepository()->find($data['idClient']);

            /** @var PaymentMethod $paymentMethod */
            $paymentMethod = $this->getPaymentMethodRepository()->find($data['idModeReglement']);

            $bill
                ->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $data['dateCreation']))
                ->setPaidAt(\DateTime::createFromFormat('Y-m-d', $data['datePaiement']))
                ->setCustomer($customer)
                ->setPaymentMethod($paymentMethod)
                ->setCustomerAddressVersion($this->getAddressRepository()->getCurrentVersion($customer->getAddress()));

            $this->getEntityManager()->flush();

            $this->getDatabaseConnection()->update('cpta_bill', array(
                "id" => $data['id'],
            ), array('id' => $bill->getId()));

            $autoIncrement = $data['id'] + 1;
            $this->getDatabaseConnection()->exec("ALTER TABLE cpta_bill AUTO_INCREMENT = " . $autoIncrement );

        }



        $output->writeln("<comment>" . $createdItemCount . " items created</comment>");
        $output->writeln("<comment>" . $updatedItemCount . " items updated</comment>");
        $output->writeln("<info>Importing bills OK</info>");
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
     * @return BillManager
     */
    protected function getBillManager()
    {
        return $this->getContainer()->get('app.manager.bill');
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getPaymentMethodRepository()
    {
        return $this->getEntityManager()->getRepository('JDJComptaBundle:PaymentMethod');
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getCustomerRepository()
    {
        return $this->getEntityManager()->getRepository('JDJComptaBundle:Customer');
    }

    /**
     * @return AddressRepository
     */
    protected function getAddressRepository()
    {
        return $this->getEntityManager()->getRepository('JDJComptaBundle:Address');
    }
}