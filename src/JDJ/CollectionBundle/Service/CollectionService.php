<?php

namespace JDJ\CollectionBundle\Service;


use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use JDJ\CollectionBundle\Entity\Collection;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserBundle\Entity\User;

class CollectionService
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
     * This function create a collection
     *
     * @return Collection
     */
    public function createCollection(Jeu $jeu, User $user, $name, $description)
    {

        //sets the fields
        $collection = new Collection();
        $collection->addJeu($jeu);
        $collection->setUser($user);
        $collection->setName($name);
        $collection->setDescription($description);

        return $collection;
    }


    /**
     * This function adds a game to a collection
     *
     * @param Jeu $jeu
     * @param Collection $collection
     * @return Collection
     */
    public function addGameCollection(Jeu $jeu, Collection $collection)
    {
        $collection->addJeu($jeu);

        return $collection;
    }


    /**
     * This function saves the collection
     *
     * @param Collection $collection
     */
    public function saveCollection(Collection $collection)
    {
        $this->em->persist($collection);
        $this->em->flush();
    }


    /**
     * This function returns the list of the user collections
     *
     * @param User $user
     * @return mixed
     */
    public function getUserCollection(User $user)
    {
        $tabCollection = $this->repo->findBy(
            array(
                "user" => $user,
            )
        );

        return $tabCollection;
    }


}