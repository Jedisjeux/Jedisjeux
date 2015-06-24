<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 04/06/2015
 * Time: 13:54
 */

namespace JDJ\ComptaBundle\EventListener;


use JDJ\ComptaBundle\Entity\Bill;
use JDJ\ComptaBundle\Entity\Manager\BookEntryManager;
use JDJ\ComptaBundle\Entity\Manager\SubscriptionManager;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class BillEventListener
{
    /**
     * @var BookEntryManager
     */
    private $bookEntryManager;

    /**
     * @var SubscriptionManager
     */
    private $subscriptionManager;

    /**
     * Constructor
     *
     * @param BookEntryManager $bookEntryManager
     * @param SubscriptionManager $subscriptionManager
     */
    public function __construct(BookEntryManager $bookEntryManager, SubscriptionManager $subscriptionManager)
    {
        $this->bookEntryManager = $bookEntryManager;
        $this->subscriptionManager = $subscriptionManager;
    }

    /**
     * @param GenericEvent $event
     */
    public function createBookEntry(GenericEvent $event)
    {
        $bill = $event->getSubject();

        if (!$bill instanceof Bill) {
            throw new UnexpectedTypeException(
                $bill,
                'JDJ\ComptaBundle\Entity\Bill'
            );
        }

        $this->bookEntryManager->createFromBill($bill);
    }

    public function createSubscriptions(GenericEvent $event)
    {
        $bill = $event->getSubject();

        if (!$bill instanceof Bill) {
            throw new UnexpectedTypeException(
                $bill,
                'JDJ\ComptaBundle\Entity\Bill'
            );
        }

        $this->subscriptionManager->createFromBill($bill);
    }
}