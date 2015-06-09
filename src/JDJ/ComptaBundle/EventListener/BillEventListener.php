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
     * Constructor
     *
     * @param BookEntryManager $bookEntryManager
     */
    public function __construct(BookEntryManager $bookEntryManager)
    {
        $this->bookEntryManager = $bookEntryManager;
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
}