<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 27/05/2015
 * Time: 00:13
 */

namespace JDJ\ComptaBundle\Entity\Repository;


use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use JDJ\ComptaBundle\Entity\Product;
use JDJ\CoreBundle\Entity\EntityRepository;

class ProductRepository extends EntityRepository
{
    /**
     * @return LogEntryRepository
     */
    public function getLogEntryRepository()
    {
        return $this->_em->getRepository('Gedmo\Loggable\Entity\LogEntry');
    }

    public function getCurrentVersion(Product $product)
    {
        $logs = $this->getLogEntryRepository()->getLogEntries($product);
        return count($logs) > 0 ? $logs[0]->getVersion() : null; // or return $log for entire object
    }
}