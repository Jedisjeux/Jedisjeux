<?php

namespace JDJ\JediZoneBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Product\Model\ProductInterface;


/**
 * status service
 */
class StatusService
{

    /**
     * Holds the Doctrine entity manager for database interaction
     * @var EntityManager
     */
    protected $em;

    /**
     * Entity-specific repo, useful for finding entities, for example
     * @var EntityRepository
     */
    protected $repo;

    /**
     * The Fully-Qualified Class Name for our entity
     * @var string
     */
    protected $class;


    /**
     * Constructor
     *
     * @param EntityManager $em
     * @param $class
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->class = $class;
        $this->repo = $em->getRepository($class);
    }

    /**
     * This function change a game status
     *
     * @param ProductInterface $jeu
     * @param String $status
     * @return bool
     */
    public function changeGameStatus(ProductInterface $jeu, $status)
    {
        $jeu->setStatus($status);

        $this->em->persist($jeu);
        $this->em->flush();

        return $jeu;
    }



}
