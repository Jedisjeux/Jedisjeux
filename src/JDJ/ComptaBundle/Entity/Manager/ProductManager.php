<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 26/05/2015
 * Time: 12:01
 */

namespace JDJ\ComptaBundle\Entity\Manager;


use Doctrine\ORM\EntityManager;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use JDJ\ComptaBundle\Entity\Product;
use JDJ\ComptaBundle\Entity\Repository\ProductRepository;

class ProductManager
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
     * @return ProductRepository
     */
    public function getProductRepository()
    {
        return $this->entityManager->getRepository('JDJComptaBundle:Product');
    }

    /**
     * @param Product $product
     * @param int $version
     */
    public function revertToVersion(Product $product, $version)
    {
        $this
            ->getLogEntryRepository()
            ->revert($product, $version);
    }

    /**
     * @param $product
     * @return int
     */
    public function getCurrentVersion(Product $product)
    {
        return $this
            ->getProductRepository()
            ->getCurrentVersion($product);
    }
}