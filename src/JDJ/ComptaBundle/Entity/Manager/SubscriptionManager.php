<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 04/06/2015
 * Time: 09:37
 */

namespace JDJ\ComptaBundle\Entity\Manager;


use Doctrine\ORM\EntityManager;
use JDJ\ComptaBundle\Entity\Bill;
use JDJ\ComptaBundle\Entity\BillProduct;
use JDJ\ComptaBundle\Entity\Customer;
use JDJ\ComptaBundle\Entity\Product;
use JDJ\ComptaBundle\Entity\Subscription;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class SubscriptionManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Subscription $subscription
     * @return \DateTime
     */
    public function calculateEndingDate(Subscription $subscription)
    {
        $endAt = clone $subscription->getStartAt();
        $endAt->add(new \DateInterval('P' . $subscription->getProduct()->getSubscriptionDuration() . 'M'));
        return $endAt;
    }

    public function endRenewalByProductAndCustomer(Product $product, Customer $customer)
    {
        $subcriptions = $this->entityManager->getRepository('JDJComptaBundle:Subscription')->findBy(array(
            'product' => $product,
            'customer' => $customer,
            'toBeRenewed' => true,
        ));

        /** @var Subscription $subcription */
        foreach ($subcriptions as $subcription) {
            $subcription->setToBeRenewed(false);
        }

        $this->entityManager->flush();
    }
}