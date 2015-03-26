<?php

namespace JDJ\CollectionBundle\Service;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use JDJ\CollectionBundle\Entity\UserGameAttribute;
use JDJ\CollectionBundle\Manager\UserGameAttributeManager;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserBundle\Entity\User;


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
     * @var EntityRepository
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
     * @param Jeu $jeu
     * @param User $user
     * @return bool
     */
    public function favorite(Jeu $jeu, User $user)
    {
        //Checks if the user has the game in his favorites
        $userGameAttribute = $this->getUserGameAttribute($jeu, $user);
        $userGameAttribute = $this->handleFavorite($userGameAttribute, $jeu, $user);

        return $userGameAttribute->isFavorite();
    }

    /**
     * This function handles the click on owned
     *
     * @param Jeu $jeu
     * @param User $user
     * @return bool
     */
    public function owned(Jeu $jeu, User $user)
    {
        //Checks if the user has the game in his favorites
        $userGameAttribute = $this->getUserGameAttribute($jeu, $user);
        $userGameAttribute = $this->handleOwned($userGameAttribute, $jeu, $user);

        return $userGameAttribute->isOwned();
    }

    /**
     * This function handles the click on wanted
     *
     * @param Jeu $jeu
     * @param User $user
     * @return bool
     */
    public function wanted(Jeu $jeu, User $user)
    {
        //Checks if the user has the game in his favorites
        $userGameAttribute = $this->getUserGameAttribute($jeu, $user);
        $userGameAttribute = $this->handleWanted($userGameAttribute, $jeu, $user);

        return $userGameAttribute->isOwned();
    }

    /**
     * This function handles the click on played
     *
     * @param Jeu $jeu
     * @param User $user
     * @return bool
     */
    public function played(Jeu $jeu, User $user)
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
     * @param Jeu $jeu
     * @param User $user
     * @return UserGameAttribute
     */
    public function handleFavorite(UserGameAttribute $userGameAttribute = null, Jeu $jeu, User $user)
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
            $userGameAttribute = new UserGameAttribute();
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
     * @param Jeu $jeu
     * @param User $user
     * @return UserGameAttribute
     */
    public function handleOwned(UserGameAttribute $userGameAttribute = null, Jeu $jeu, User $user)
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
            $userGameAttribute = new UserGameAttribute();
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
     * @param Jeu $jeu
     * @param User $user
     * @return UserGameAttribute
     */
    public function handleWanted(UserGameAttribute $userGameAttribute = null, Jeu $jeu, User $user)
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
            $userGameAttribute = new UserGameAttribute();
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
     * @param Jeu $jeu
     * @param User $user
     * @return UserGameAttribute
     */
    public function handlePlayed(UserGameAttribute $userGameAttribute = null, Jeu $jeu, User $user)
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
            $userGameAttribute = new UserGameAttribute();
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
     * @param Jeu $jeu
     * @param User $user
     * @return mixed
     */
    public function getUserGameAttribute(Jeu $jeu, User $user)
    {

        if (!$jeu || !$user) {
            throw $this->createNotFoundException('Unable to find Game or User entity.');
        }

        $userGameAttribute = $this->repo->findOneUserGameAttribute($jeu, $user);

        return $userGameAttribute;
    }

    /**
     * This function get the user favorites
     *
     * @param User $user
     * @return mixed
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
     * @return mixed
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
     * @return mixed
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
     * @return mixed
     */
    public function getPlayed(User $user)
    {
        $tabUserGameAttribute = $this->repo->findPlayed($user);

        return $tabUserGameAttribute;
    }



}
