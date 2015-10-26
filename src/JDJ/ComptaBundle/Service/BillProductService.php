<?php

namespace JDJ\ComptaBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use JDJ\CollectionBundle\Entity\Collection;
use JDJ\CollectionBundle\Entity\ListElement;
use JDJ\ComptaBundle\Entity\Bill;
use JDJ\ComptaBundle\Entity\BillProduct;
use JDJ\ComptaBundle\Entity\Manager\ProductManager;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\VarDumper\VarDumper;

class BillProductService
{
    /**
     * Holds the Doctrine entity manager for database interaction
     * @var EntityManager
     */
    protected $em;

    /**
     * Entity-specific repo, useful for finding entities, for example
     * @var EntityRepository
     */
    protected $repo;

    /**
     * The Fully-Qualified Class Name for our entity
     * @var string
     */
    protected $class;

    /**
     * @var ProductManager
     */
    protected $productManager;

    /**
     * Constructor
     *
     * @param EntityManager $em
     * @param ProductManager $productManager
     * @param $class
     */
    public function __construct(EntityManager $em, ProductManager $productManager, $class)
    {
        $this->em = $em;
        $this->class = $class;
        $this->productManager = $productManager;
        $this->repo = $em->getRepository($class);
    }

    /**
     * Fill in the data coming from the form collection
     *
     * @param Bill $bill
     *
     * @return Bill
     */
    public function fillBillProduct(Bill $bill)
    {
        $billProducts = $bill->getBillProducts();
        if (!empty($billProducts)) {
            foreach ($billProducts as $billProduct) {

                //Gets the current product version
                $currentVersion = $this
                    ->productManager
                    ->getCurrentVersion($billProduct->getProduct());

                //Sets the datas
                $billProduct
                    ->setBill($bill)
                    ->setProductVersion($currentVersion);
            }
        }

        return $bill;
    }


    /**
     * Fill in the products for the edit form
     *
     * @param Bill $bill
     * @param Form $editForm
     *
     * @return Bill
     */
    public function setProductsBill(Bill $bill, Form $editForm)
    {

        foreach ($bill->getBillProducts() as $billProduct) {
            $this->em->remove($billProduct);
        }

        $bill->setBillProducts(new ArrayCollection());
        $this->em->flush();

        $billProducts = $editForm->get('billProducts')->getData();

        /** @var billProduct $billProduct */
        foreach ($billProducts as $billProduct) {

            $product = $billProduct->getProduct();

            //Gets the current product version
            $currentVersion = $this
                ->productManager
                ->getCurrentVersion($product);

            $billProduct
                ->setProductVersion($currentVersion)
                ->setBill($bill);

            $bill->addBillProduct($billProduct);

        }


        return $bill;
    }


}