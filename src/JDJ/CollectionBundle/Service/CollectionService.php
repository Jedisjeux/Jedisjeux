<?php

namespace JDJ\CollectionBundle\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JDJ\CollectionBundle\Entity\Collection;
use JDJ\CollectionBundle\Entity\ListElement;
use JDJ\UserBundle\Entity\User;
use Sylius\Component\Product\Model\ProductInterface;

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
     * @param User $user
     * @param $name
     * @param $description
     * @return Collection
     */
    public function createCollection(User $user, $name, $description)
    {

        //sets the fields
        $collection = new Collection();
        $collection->setUser($user);
        $collection->setName($name);
        $collection->setDescription($description);

        return $collection;
    }


    /**
     * This function adds a game to a collection
     *
     * @param ProductInterface $jeu
     * @param Collection $collection
     * @return Collection
     */
    public function addGameCollection(ProductInterface $jeu, Collection $collection)
    {

        if (!$this->checkJeuCollection($jeu, $collection)) {
            $listElement = new ListElement();
            $listElement->setJeu($jeu);
            $listElement->setCollection($collection);

            $collection->addListElement($listElement);
        }

        return $collection;
    }


    /**
     * This function saves the collection
     *
     * @param Collection $collection
     * @return Collection
     */
    public function saveCollection(Collection $collection)
    {
        $this->em->persist($collection);
        $this->em->flush();

        return $collection;
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

    /**
     * this function prepare the collection list to be sent to the front in json
     *
     * @param $tabCollection
     * @return array
     */
    public function prepareCollectionJsonData($tabCollection)
    {

        $jsonCollection = array();
        /** @var Collection $collection */
        foreach($tabCollection as $collection)
        {
            $jsonCollection[] = array(
                "id" => $collection->getId(),
                "name" => $collection->getName(),
            );
        }

        return $jsonCollection;
    }

    /**
     * This function checks if the game is already in the collection
     *
     * @param ProductInterface $jeu
     * @param Collection $collection
     * @return bool
     */
    private function checkJeuCollection(ProductInterface $jeu, Collection $collection)
    {
        $isJeuInCollection = false;

        /**
         * iterate on the listElements of the collection to compare the game id
         */
        foreach ($collection->getListElements() as $listElement) {

            if($listElement->getJeu()->getId() === $jeu->getId()) {
                $isJeuInCollection = true;
            }
        }

        return $isJeuInCollection;
    }

}