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
class BillProductsImportCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('compta:bill-products:import')
            ->setDescription('Import bills from old jedisjeux')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Importing bills from old jedisjeux");
        $query = <<<EOM
select      produitFacture.idFacture,
            produitFacture.idProduit,
            produitFacture.prixUnitaire,
            produitFacture.quantite,
            produit.libelle
from        zf_jedisjeux_test.cpta_produit produit
inner join  zf_jedisjeux_test.cpta_produit_facture produitFacture on produitFacture.idProduit = produit.id
order by    produitFacture.idFacture
EOM;

        $oldItems = $this->getDatabaseConnection()->fetchAll($query);

        $createdItemCount = 0;
        $updatedItemCount = 0;

        foreach($oldItems as $data) {

            /** @var Bill $bill */
            $bill = $this
                ->getBillManager()
                ->getBillRepository()
                ->find($data['idFacture']);

            /** @var Product $product */
            $product = $this->getProductRepository()->find($data['idProduit']);

            if (null === $product) {
                $product = new Product();
                $product
                    ->setName($data['libelle'] . ' - 12 mois')
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
                $this->getEntityManager()->persist($product);
                $this->getEntityManager()->flush();
            }


            $billProduct = $this->getBillProductRepository()->findOneBy(array(
                'product' => $product->getId(),
                'bill' => $bill->getId(),
            ));

            if (null === $billProduct) {
                $createdItemCount ++;
                $billProduct = new BillProduct();
            } else {
                $updatedItemCount ++;
            }

            $billProduct
                ->setBill($bill)
                ->setProduct($product)
                ->setProductVersion($this->getProductRepository()->getCurrentVersion($product))
                ->setQuantity($data['quantite']);

            $this->getEntityManager()->persist($billProduct);
            $this->getEntityManager()->flush();
        }

        $output->writeln("<comment>" . $createdItemCount . " items created</comment>");
        $output->writeln("<comment>" . $updatedItemCount . " items updated</comment>");
        $output->writeln("<info>Importing bill products OK</info>");
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
}