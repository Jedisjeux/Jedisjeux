<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 04/06/2015
 * Time: 13:41
 */

namespace JDJ\ComptaBundle\Event;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class BillEvents
{
    const BILL_POST_CREATE = 'bill.post_create';

    const BILL_UPDATED = 'app.bill.updated';
}