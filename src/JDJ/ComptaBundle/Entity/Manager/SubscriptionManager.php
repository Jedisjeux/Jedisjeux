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
        foreach ($bill->getBillProducts() as $billProduct) {
            $this->createFromBillProduct($billProduct);
        }
    }

    /**
     * @param BillProduct $billProduct
     */
    public function createFromBillProduct(BillProduct $billProduct)
    {
        if ($billProduct->getQuantity() === count($billProduct->getSubscriptions())) {
            return;
        }

        // remove all current subscriptions
        foreach ($billProduct->getSubscriptions() as $subscription) {
            $this->entityManager->remove($subscription);
        }

        $this->entityManager->flush();

        $bill = $billProduct->getBill();

        for ($i = 0; $i < $billProduct->getQuantity(); $i++) {
            $subscription = new Subscription();

            $subscription
                ->setBillProduct($billProduct)
                ->setCustomer($bill->getCustomer())
                ->setStatus(Subscription::WAITING_FOR_PAYMENT);

            $this->entityManager->persist($subscription);
        }

        $this->entityManager->flush();
    }
}