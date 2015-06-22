<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 27/05/2015
 * Time: 20:14
 */

namespace JDJ\ComptaBundle\Entity\Manager;


use Doctrine\ORM\EntityManager;
use JDJ\ComptaBundle\Entity\Bill;
use JDJ\ComptaBundle\Entity\BillProduct;
use JDJ\ComptaBundle\Entity\Repository\BillRepository;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class BillManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ProductManager
     */
    private $productManager;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager
     * @param ProductManager $productManager
     */
    public function __construct(EntityManager $entityManager, ProductManager $productManager)
    {
        $this->entityManager = $entityManager;
        $this->productManager = $productManager;
    }

    /**
     * @return BillRepository
     */
    public function getBillRepository()
    {
        return $this
            ->entityManager
            ->getRepository('JDJComptaBundle:Bill');
    }

    /**
     * @param Bill $bill
     * @return float
     */
    public function getTotalPrice(Bill $bill)
    {
        $totalPrice = 0.00;

        /** @var BillProduct $billProduct */
        foreach ($bill->getBillProducts() as $billProduct) {
            $this->productManager->revertToVersion($billProduct->getProduct(), $billProduct->getProductVersion());
            $totalPrice += $billProduct->getProduct()->getPrice() * $billProduct->getQuantity();
            $this->entityManager->detach($billProduct->getProduct());
        }

        return $totalPrice;
    }
}