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
select      facture.id, facture.idModeReglement, facture.idClient, facture.dateCreation, facture.datePaiement,
            produitFacture.idProduit, produitFacture.prixUnitaire, produitFacture.quantite, produit.libelle
from        zf_jedisjeux_test.cpta_produit produit
inner join  zf_jedisjeux_test.cpta_produit_facture produitFacture on produitFacture.idProduit = produit.id
inner join  zf_jedisjeux_test.cpta_facture facture on facture.id = produitFacture.idFacture
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

            /** @var Product $product */
            $product = $this->getProductRepository()->find($data['idProduit']);

            $bill
                ->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $data['dateCreation']))
                ->setPaidAt(\DateTime::createFromFormat('Y-m-d', $data['datePaiement']))
                ->setCustomer($customer)
                ->setPaymentMethod($paymentMethod)
                ->setCustomerAddressVersion($this->getAddressRepository()->getCurrentVersion($customer->getAddress()));

            if (null === $product) {
                $product = new Product();
                $product
                    ->setName($data['libelle'])
                    ->setPrice($data['prixUnitaire'])
                    ->setSubscriptionDuration(12);

                $this->getEntityManager()->persist($product);
                $this->getEntityManager()->flush();

                $this->getDatabaseConnection()->update('cpta_product', array(
                    "id" => $data['idProduit'],
                ), array('id' => $product->getId()));

                $this->getDatabaseConnection()->update('ext_log_entries', array(
                    'object_id' => $data['idProduit'],
                ), array(
                    'object_id' => $product->getId(),
                    'object_class' => 'JDJ\ComptaBundle\Entity\Product',
                ));

                $product->setId($data['idProduit']);

                $autoIncrement = $data['idProduit'] + 1;
                $this->getDatabaseConnection()->exec("ALTER TABLE cpta_product AUTO_INCREMENT = " . $autoIncrement );
            }

            if ($data['prixUnitaire'] !== $product->getPrice()) {
                $product
                    ->setPrice($data['prixUnitaire']);
                $this->getEntityManager()->flush();
            }

            $billProduct = $this->getBillProductRepository()->findOneBy(array(
                'product' => $data['idProduit'],
                'bill' => $data['id'],
            ));

            if (null === $billProduct) {
                $billProduct = new BillProduct();
            }

            $billProduct
                ->setBill($bill)
                ->setProduct($product)
                ->setProductVersion($this->getProductRepository()->getCurrentVersion($product))
                ->setQuantity($data['quantite']);

            $bill->addBillProduct($billProduct);
            $this->getEntityManager()->flush();

            $stmt = $this->getEntityManager()->getConnection()
                ->prepare('SET foreign_key_checks = 0;');

            $stmt->execute();

            $this->getDatabaseConnection()->update('cpta_bill', array(
                "id" => $data['id'],
            ), array('id' => $product->getId()));

            $this->getDatabaseConnection()->update('cpta_bill_product', array(
                "bill_id" => $data['id'],
            ), array('bill_id' => $product->getId()));

            $stmt = $this->getEntityManager()->getConnection()
                ->prepare('SET foreign_key_checks = 0;');

            $stmt->execute();

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
     * @return ProductRepository
     */
    protected function getProductRepository()
    {
        return $this->getEntityManager()->getRepository('JDJComptaBundle:Product');
    }

    /**
     * @return EntityRepository
     */
    protected function getBillProductRepository()
    {
        return $this->getEntityManager()->getRepository('JDJComptaBundle:BillProduct');
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