<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 09/06/2015
 * Time: 16:33
 */

namespace JDJ\ComptaBundle\Behat;


use Behat\Gherkin\Node\TableNode;
use JDJ\ComptaBundle\Entity\Bill;
use JDJ\ComptaBundle\Entity\BillProduct;
use JDJ\ComptaBundle\Entity\Customer;
use JDJ\ComptaBundle\Entity\Manager\ProductManager;
use JDJ\ComptaBundle\Entity\Product;
use JDJ\CoreBundle\Behat\DefaultContext;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class BillProductContext extends DefaultContext
{
    /**
     * @Given /^bill from customer "([^""]*)" has following products:$/
     *
     * @param $companyName
     * @param TableNode $productsTable
     */
    public function billHasFollowingProducts($companyName, TableNode $productsTable)
    {
        $manager = $this->getEntityManager();

        /** @var Customer $customer */
        $customer = $this->findOneBy('comptaCustomer', array('companyName' => $companyName));

        /** @var Bill $bill */
        $bill = $this->findOneBy('comptaBill', array('customer' => $customer));

        foreach ($productsTable->getHash() as $data) {

            /** @var Product $product */
            $product = $this->findOneBy('comptaProduct', array('name' => $data['name']));

            $billProduct = new BillProduct();
            $billProduct
                ->setProduct($product)
                ->setProductVersion($this->getProductManager()->getCurrentVersion($product))
                ->setQuantity(isset($data['quantity']) ? $data['quantity'] : $this->faker->numberBetween());

            $bill->addBillProduct($billProduct);

            $manager->persist($billProduct);
        }

        $manager->flush();
    }

    /**
     * @return ProductManager
     */
    public function getProductManager()
    {
        return $this->getContainer()->get('app.manager.product');
    }
}