<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 04/06/2015
 * Time: 16:40
 */

namespace JDJ\CoreBundle\Entity\Repository;


use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use JDJ\CoreBundle\Entity\EntityRepository;

/**
 * Class LoggableEntityRepository
 */
class LoggableEntityRepository extends EntityRepository
{
    /**
     * @return LogEntryRepository
     */
    public function getLogEntryRepository()
    {
        return $this->_em->getRepository('Gedmo\Loggable\Entity\LogEntry');
    }

    /**
     * @param $entity
     * @return null|int
     */
    public function getCurrentVersion($entity)
    {
        $logs = $this->getLogEntryRepository()->getLogEntries($entity);
        return count($logs) > 0 ? $logs[0]->getVersion() : null; // or return $log for entire object
    }
}