<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 04/06/2015
 * Time: 16:24
 */

namespace JDJ\ComptaBundle\Entity\Manager;


use Doctrine\ORM\EntityManager;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use JDJ\ComptaBundle\Entity\Address;
use JDJ\ComptaBundle\Entity\Repository\AddressRepository;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class AddressManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return LogEntryRepository
     */
    public function getLogEntryRepository()
    {
        return $this->entityManager->getRepository('Gedmo\Loggable\Entity\LogEntry');
    }

    /**
     * @return AddressRepository
     */
    public function getAddressRepository()
    {
        return $this->entityManager->getRepository('JDJComptaBundle:Address');
    }

    /**
     * @param Address $address
     * @param int $version
     */
    public function revertToVersion(Address $address, $version)
    {
        $this
            ->getLogEntryRepository()
            ->revert($address, $version);
    }

    /**
     * @param Address $address
     * @return int
     */
    public function getCurrentVersion(Address $address)
    {
        return $this
            ->getAddressRepository()
            ->getCurrentVersion($address);
    }
}