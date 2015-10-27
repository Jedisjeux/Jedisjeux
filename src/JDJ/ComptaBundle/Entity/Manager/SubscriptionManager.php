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
use JDJ\ComptaBundle\Entity\Repository\SubscriptionRepository;
use JDJ\ComptaBundle\Entity\Subscription;
use Symfony\Component\VarDumper\VarDumper;

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
     * @return SubscriptionRepository
     */
    public function getSubscriptionRepository()
    {
        return $this->entityManager->getRepository('JDJComptaBundle:Subscription');
    }

    /**
     * Calculate the ending date of a subscription
     *
     * @param Subscription $subscription
     * @return \DateTime
     */
    public function calculateEndingDate(Subscription $subscription)
    {
        $endAt = clone $subscription->getStartAt();
        $endAt->add(new \DateInterval('P' . $subscription->getProduct()->getSubscriptionDuration() . 'M'));
        return $endAt;
    }

    /**
     * Create subscriptions from bill informations
     * Also remove useless subscriptions if bill is updated
     *
     * @param Bill $bill
     */
    public function createFromBill(Bill $bill)
    {
        $subscriptionsToRemove = array();
        $subscriptions = $bill->getSubscriptions();

        if ($subscriptions) {
            foreach ($subscriptions as $subscription) {
                $subscriptionsToRemove[$subscription->getId()] = $subscription;
            }
        }

        $billProducts = $bill->getBillProducts();
        if ($billProducts) {
            foreach ($billProducts as $billProduct) {

                $subscription = $this->getSubscriptionFromBillProduct($billProduct);

                if (null === $subscription) {
                    $subscription = new Subscription();
                    $this->entityManager->persist($subscription);
                } else {
                    unset($subscriptionsToRemove[$subscription->getId()]);
                }

                $subscription
                    ->setBill($bill)
                    ->setProduct($billProduct->getProduct())
                    ->setCustomer($bill->getCustomer())
                    ->setStatus(null === $bill->getPaidAt() ? Subscription::WAITING_FOR_PAYMENT : Subscription::WAITING_FOR_INSTALLATION);

            }
        }


        foreach ($subscriptionsToRemove as $subscription) {
            $this->entityManager->remove($subscription);
        }

        $this->entityManager->flush();
    }

    /**
     * Get a subscription to a product from a bill
     *
     * @param BillProduct $billProduct
     * @return Subscription|null
     */
    public function getSubscriptionFromBillProduct(BillProduct $billProduct)
    {
        $bill = $billProduct->getBill();
        $product = $billProduct->getProduct();
        $subscriptions = $bill->getSubscriptions();
        if ($subscriptions) {
            foreach ($bill->getSubscriptions() as $subscription) {
                if ($subscription->getProduct()->getId() === $product->getId()) {
                    return $subscription;
                }
            }
        }
        return null;
    }
}