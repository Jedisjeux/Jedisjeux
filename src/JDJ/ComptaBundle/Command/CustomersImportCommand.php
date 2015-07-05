<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 15/06/15
 * Time: 20:24
 */

namespace JDJ\ComptaBundle\Command;

use Doctrine\ORM\EntityManager;
use JDJ\ComptaBundle\Entity\Address;
use JDJ\ComptaBundle\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class CustomersImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('compta:customers:import')
            ->setDescription('Import customers from old jedisjeux')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Importing customers from old jedisjeux");
        $query = <<<EOM
select      old.*
from        zf_jedisjeux_test.cpta_client old
EOM;

        $oldItems = $this->getDatabaseConnection()->fetchAll($query);
        $output->writeln("<comment>total of " . count($oldItems) . " items</comment>");

        $createdItemCount = 0;
        $updatedItemCount = 0;

        foreach($oldItems as $data) {

            /** @var Customer $customer */
            $customer = $this->getCustomerRepository()->find($data['id']);
            if (null === $customer) {
                $customer = new Customer();
                $this->getEntityManager()->persist($customer);
                $createdItemCount ++;
            } else {
                $updatedItemCount ++;
            }

            $customer
                ->setId($data['id'])
                ->setCompanyName($data['societe'])
                ->setEmail($data['email']);

            $address = $customer->getAddress();
            if (null === $address) {
                $address = new Address();
                $customer->setAddress($address);
                $this->getEntityManager()->persist($address);
            }

            $address
                ->setStreet($data['rue'])
                ->setPostalCode($data['codePostal'])
                ->setCity($data['ville']);

            $this->getEntityManager()->flush();

            $this->getDatabaseConnection()->update('cpta_customer', array(
                "id" => $data['id'],
            ), array('id' => $customer->getId()));

            $autoIncrement = $data['id'] + 1;
            $this->getDatabaseConnection()->exec("ALTER TABLE cpta_customer AUTO_INCREMENT = " . $autoIncrement );

            $customer
                ->setId($data['id']);


            $this->getEntityManager()->flush();

        }

        $output->writeln("<comment>" . $createdItemCount . " items created</comment>");
        $output->writeln("<comment>" . $updatedItemCount . " items updated</comment>");
        $output->writeln("<info>Importing customers OK</info>");
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
    protected function getCustomerRepository()
    {
        return $this->getEntityManager()->getRepository('JDJComptaBundle:Customer');
    }
}