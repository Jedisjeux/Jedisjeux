<?php

namespace JDJ\CollectionBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use JDJ\CollectionBundle\Entity\UserProductAttribute;
use JDJ\CollectionBundle\Manager\UserGameAttributeManager;
use JDJ\CollectionBundle\Repository\UserGameAttributeRepository;
use JDJ\UserBundle\Entity\User;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Collection
 */
class UserGameAttributeService
{

    /**
     * Holds the Doctrine entity manager for database interaction
     * @var EntityManager
     */
    protected $em;

    /**
     * Entity-specific repo, useful for finding entities, for example
     * @var UserGameAttributeRepository
     */
    protected $repo;

    /**
     * The Fully-Qualified Class Name for our entity
     * @var string
     */
    protected $class;

    /**
     * @var UserGameAttributeManager
     */
    private $manager;


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
        $this->manager = new UserGameAttributeManager($this->em);
    }

    /**
     * This function handles the click on favorite
     *
     * @param ProductInterface $jeu
     * @param User $user
     * @return bool
     */
    public function favorite(ProductInterface $jeu, User $user)
    {
        //Checks if the user has the game in his favorites
        $userGameAttribute = $this->getUserGameAttribute($jeu, $user);
        $userGameAttribute = $this->handleFavorite($userGameAttribute, $jeu, $user);

        return $userGameAttribute->isFavorite();
    }

    /**
     * This function handles the click on owned
     *
     * @param ProductInterface $jeu
     * @param User $user
     * @return bool
     */
    public function owned(ProductInterface $jeu, User $user)
    {
        //Checks if the user has the game in his favorites
        $userGameAttribute = $this->getUserGameAttribute($jeu, $user);
        $userGameAttribute = $this->handleOwned($userGameAttribute, $jeu, $user);

        return $userGameAttribute->isOwned();
    }

    /**
     * This function handles the click on wanted
     *
     * @param ProductInterface $jeu
     * @param User $user
     * @return bool
     */
    public function wanted(ProductInterface $jeu, User $user)
    {
        //Checks if the user has the game in his favorites
        $userGameAttribute = $this->getUserGameAttribute($jeu, $user);
        $userGameAttribute = $this->handleWanted($userGameAttribute, $jeu, $user);

        return $userGameAttribute->isOwned();
    }

    /**
     * This function handles the click on played
     *
     * @param ProductInterface $jeu
     * @param User $user
     * @return bool
     */
    public function played(ProductInterface $jeu, User $user)
    {
        //Checks if the user has the game in his favorites
        $userGameAttribute = $this->getUserGameAttribute($jeu, $user);
        $userGameAttribute = $this->handlePlayed($userGameAttribute, $jeu, $user);

        return $userGameAttribute->isOwned();
    }


    /**
     * This function handles the different cases for putting a game to favorite
     *
     * @param UserGameAttribute $userGameAttribute
     * @param ProductInterface $jeu
     * @param User $user
     *
     * @return UserGameAttribute
     */
    public function handleFavorite(UserProductAttribute $userGameAttribute = null, ProductInterface $jeu, User $user)
    {
        //Set the game to favorite or not
        if ($userGameAttribute) {
            if ($userGameAttribute->isFavorite()) {
                $userGameAttribute->setFavorite(false);
            } else {
                $userGameAttribute->setFavorite(true);
            }
            $this->manager->record($userGameAttribute);

        } else {
            /**
             * Create
             */
            $userGameAttribute = new UserProductAttribute();
            $userGameAttribute->setFavorite(true);
            $userGameAttribute->setJeu($jeu);
            $userGameAttribute->setUser($user);

            $this->manager->record($userGameAttribute);
        }

        return $userGameAttribute;
    }

    /**
     * This function handles the different cases for putting a game he owned
     *
     * @param UserGameAttribute $userGameAttribute
     * @param ProductInterface $jeu
     * @param User $user
     *
     * @return UserGameAttribute
     */
    public function handleOwned(UserProductAttribute $userGameAttribute = null, ProductInterface $jeu, User $user)
    {

        //Set the game to favorite or not
        if ($userGameAttribute) {
            if ($userGameAttribute->isOwned()) {
                $userGameAttribute->setOwned(false);
            } else {
                $userGameAttribute->setOwned(true);
            }
            $this->manager->record($userGameAttribute);
        } else {
            /**
             * Create
             */
            $userGameAttribute = new UserProductAttribute();
            $userGameAttribute->setOwned(true);
            $userGameAttribute->setJeu($jeu);
            $userGameAttribute->setUser($user);

            $this->manager->record($userGameAttribute);
        }
        return $userGameAttribute;
    }

    /**
     * This function handles the different cases for putting a game he wants
     *
     * @param UserGameAttribute $userGameAttribute
     * @param ProductInterface $jeu
     * @param User $user
     *
     * @return UserGameAttribute
     */
    public function handleWanted(UserProductAttribute $userGameAttribute = null, ProductInterface $jeu, User $user)
    {

        //Set the game to favorite or not
        if ($userGameAttribute) {
            if ($userGameAttribute->isWanted()) {
                $userGameAttribute->setWanted(false);
            } else {
                $userGameAttribute->setWanted(true);
            }
            $this->manager->record($userGameAttribute);
        } else {
            /**
             * Create
             */
            $userGameAttribute = new UserProductAttribute();
            $userGameAttribute->setWanted(true);
            $userGameAttribute->setJeu($jeu);
            $userGameAttribute->setUser($user);

            $this->manager->record($userGameAttribute);
        }
        return $userGameAttribute;
    }

    /**
     * This function handles the different cases for putting a game he has played
     *
     * @param UserGameAttribute $userGameAttribute
     * @param ProductInterface $jeu
     * @param User $user
     *
     * @return UserGameAttribute
     */
    public function handlePlayed(UserProductAttribute $userGameAttribute = null, ProductInterface $jeu, User $user)
    {

        //Set the game to favorite or not
        if ($userGameAttribute) {
            if ($userGameAttribute->hasPlayed()) {
                $userGameAttribute->setPlayed(false);
            } else {
                $userGameAttribute->setPlayed(true);
            }
            $this->manager->record($userGameAttribute);
        } else {
            /**
             * Create
             */
            $userGameAttribute = new UserProductAttribute();
            $userGameAttribute->setPlayed(true);
            $userGameAttribute->setJeu($jeu);
            $userGameAttribute->setUser($user);

            $this->manager->record($userGameAttribute);
        }
        return $userGameAttribute;
    }

    /**
     * This function get the userGameAttribute
     *
     * @param ProductInterface $jeu
     * @param User $user
     * @return array
     */
    public function getUserGameAttribute(ProductInterface $jeu, User $user)
    {

        if (!$jeu || !$user) {
            throw new NotFoundHttpException('Unable to find Game or User entity.');
        }

        $userGameAttribute = $this->repo->findOneUserGameAttribute($jeu, $user);

        return $userGameAttribute;
    }

    /**
     * This function get the user favorites
     *
     * @param User $user
     * @return array
     */
    public function getFavorites(User $user)
    {
        $tabUserGameAttribute = $this->repo->findFavorites($user);

        return $tabUserGameAttribute;
    }

    /**
     * This function get the user owned
     *
     * @param User $user
     * @return array
     */
    public function getOwned(User $user)
    {
        $tabUserGameAttribute = $this->repo->findOwned($user);

        return $tabUserGameAttribute;
    }

    /**
     * This function get the user wanted
     *
     * @param User $user
     * @return array
     */
    public function getWanted(User $user)
    {
        $tabUserGameAttribute = $this->repo->findWanted($user);

        return $tabUserGameAttribute;
    }

    /**
     * This function get the user played
     *
     * @param User $user
     * @return array
     */
    public function getPlayed(User $user)
    {
        $tabUserGameAttribute = $this->repo->findPlayed($user);

        return $tabUserGameAttribute;
    }



}
