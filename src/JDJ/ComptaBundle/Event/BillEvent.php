<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 26/10/2015
 * Time: 13:46
 */

namespace JDJ\ComptaBundle\Event;

use JDJ\ComptaBundle\Entity\Bill;
use Symfony\Component\EventDispatcher\Event;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class BillEvent extends Event
{
    /**
     * @var Bill
     */
    protected $bill;

    /**
     * BillEvent constructor.
     *
     * @param Bill $bill
     */
    public function __construct(Bill $bill)
    {
        $this->bill = $bill;
    }

    /**
     * @return Bill
     */
    public function getBill()
    {
        return $this->bill;
    }
}