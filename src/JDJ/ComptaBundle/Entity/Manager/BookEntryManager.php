<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 08/06/2015
 * Time: 14:09
 */

namespace JDJ\ComptaBundle\Entity\Manager;


use Doctrine\ORM\EntityManager;
use JDJ\ComptaBundle\Entity\Bill;
use JDJ\ComptaBundle\Entity\BookEntry;
use JDJ\ComptaBundle\Entity\Repository\BookEntryRepository;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class BookEntryManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var BillManager
     */
    private $billManager;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager
     * @param BillManager $billManager
     */
    public function __construct(EntityManager $entityManager, BillManager $billManager)
    {
        $this->entityManager = $entityManager;
        $this->billManager = $billManager;
    }

    /**
     * @return BookEntryRepository
     */
    public function getBookEntryRepository()
    {
        return $this
            ->entityManager
            ->getRepository('JDJComptaBundle:BookEntry');
    }

    /**
     * Create BookEntry from Bill data
     *
     * @param Bill $bill
     */
    public function createFromBill(Bill $bill)
    {
        $bookEntry = $bill->getBookEntry();

        if (null === $bookEntry) {
            $bookEntry = new BookEntry();
            $this->entityManager->persist($bookEntry);
        }

        $bookEntry
            ->setPrice($this->billManager->getTotalPrice($bill))
            ->setActiveAt($bill->getPaidAt())
            ->setLabel('Paiement facture ' . $bill->getCustomer())
            ->setState(BookEntry::STATE_CREDITED)
            ->setPaymentMethod($bill->getPaymentMethod());

        $bill->setBookEntry($bookEntry);

        $this->entityManager->flush();
    }

    /**
     * Remove a BookEntry created by a bill
     *
     * @param Bill $bill
     */
    public function removeFromBill(Bill $bill)
    {
        if (null !== $bill->getPaidAt()) {
            return;
        }

        $bookEntry = $bill->getBookEntry();

        if (null !== $bookEntry) {
            $bill->setBookEntry(null);
            $this->entityManager->remove($bookEntry);
            $this->entityManager->flush();
        }
    }
}