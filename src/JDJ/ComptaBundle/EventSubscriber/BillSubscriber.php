<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 04/06/2015
 * Time: 13:54
 */

namespace JDJ\ComptaBundle\EventSubscriber;


use Doctrine\Common\EventSubscriber;
use JDJ\ComptaBundle\Entity\Bill;
use JDJ\ComptaBundle\Entity\Manager\BookEntryManager;
use JDJ\ComptaBundle\Entity\Manager\SubscriptionManager;
use JDJ\ComptaBundle\Event\BillEvent;
use JDJ\ComptaBundle\Event\BillEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class BillSubscriber implements EventSubscriberInterface
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
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            BillEvents::POST_CREATE => 'onBillPostCreate',
            BillEvents::POST_UPDATE => 'onBillPostUpdate',
        );
    }

    public function onBillPostCreate(BillEvent $event)
    {
        $bill = $event->getBill();
        $this->bookEntryManager->createFromBill($bill);
        $this->subscriptionManager->createFromBill($bill);
    }

    public function onBillPostUpdate(BillEvent $event)
    {
        $bill = $event->getBill();
        //$this->bookEntryManager->createFromBill($bill);
        $this->subscriptionManager->createFromBill($bill);
    }
}