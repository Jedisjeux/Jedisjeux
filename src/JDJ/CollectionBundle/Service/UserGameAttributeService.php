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
     * @var EntityManager
     */
    protected $em;

    /**
     * @var UserGameAttributeManager
     */
    private $manager;

    /**
     * @var Jeu
     */
    private $jeu;

    /**
     * @var User
     */
    private $user;


    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->manager = new UserGameAttributeManager($this->em);
    }

    /**
     * This function handles the click on favorite
     *
     * @param $jeu_id
     * @param $user_id
     * @return bool
     */
    public function favorite($jeu_id, $user_id)
    {
        //Checks if the user has the game in his favorites
        $userGameAttribute = $this->getUserGameAttribute($jeu_id, $user_id);
        $this->handleFavorite($userGameAttribute);

        return $userGameAttribute->isFavorite();
    }

    /**
     * This function handles the click on owned
     *
     * @param $jeu_id
     * @param $user_id
     * @return bool
     */
    public function owned($jeu_id, $user_id)
    {
        //Checks if the user has the game in his favorites
        $userGameAttribute = $this->getUserGameAttribute($jeu_id, $user_id);
        $this->handleOwned($userGameAttribute);

        return $userGameAttribute->isOwned();
    }

    /**
     * This function handles the click on wanted
     *
     * @param $jeu_id
     * @param $user_id
     * @return bool
     */
    public function wanted($jeu_id, $user_id)
    {
        //Checks if the user has the game in his favorites
        $userGameAttribute = $this->getUserGameAttribute($jeu_id, $user_id);
        $this->handleWanted($userGameAttribute);

        return $userGameAttribute->isOwned();
    }

    /**
     * This function handles the click on played
     *
     * @param $jeu_id
     * @param $user_id
     * @return bool
     */
    public function played($jeu_id, $user_id)
    {
        //Checks if the user has the game in his favorites
        $userGameAttribute = $this->getUserGameAttribute($jeu_id, $user_id);
        $this->handlePlayed($userGameAttribute);

        return $userGameAttribute->isOwned();
    }


    /**
     * This function handles the different cases for putting a game to favorite
     *
     * @param $userGameAttribute
     * @return UserGameAttribute
     */
    public function handleFavorite($userGameAttribute)
    {
        //Set the game to favorite or not
        if ($userGameAttribute && (get_class($userGameAttribute) === 'JDJ\CollectionBundle\Entity\UserGameAttribute')) {
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
            $userGameAttribute = new UserGameAttribute(
                true,
                false,
                false,
                false,
                $this->getUser()->getId(),
                $this->getJeu()->getId(),
                $this->getUser(),
                $this->getJeu()
            );
            $this->manager->record($userGameAttribute);
        }
        return $userGameAttribute;
    }

    /**
     * This function handles the different cases for putting a game he owned
     *
     * @param $userGameAttribute
     * @return UserGameAttribute
     */
    public function handleOwned($userGameAttribute)
    {

        //Set the game to favorite or not
        if ($userGameAttribute && (get_class($userGameAttribute) === 'JDJ\CollectionBundle\Entity\UserGameAttribute')) {
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
            $userGameAttribute = new UserGameAttribute(
                false,
                true,
                false,
                false,
                $this->getUser()->getId(),
                $this->getJeu()->getId(),
                $this->getUser(),
                $this->getJeu()
            );

            $this->manager->record($userGameAttribute);
        }
        return $userGameAttribute;
    }

    /**
     * This function handles the different cases for putting a game he wants
     *
     * @param $userGameAttribute
     * @return UserGameAttribute
     */
    public function handleWanted($userGameAttribute)
    {

        //Set the game to favorite or not
        if ($userGameAttribute && (get_class($userGameAttribute) === 'JDJ\CollectionBundle\Entity\UserGameAttribute')) {
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
            $userGameAttribute = new UserGameAttribute(
                false,
                false,
                true,
                false,
                $this->getUser()->getId(),
                $this->getJeu()->getId(),
                $this->getUser(),
                $this->getJeu()
            );

            $this->manager->record($userGameAttribute);
        }
        return $userGameAttribute;
    }

    /**
     * This function handles the different cases for putting a game he has played
     *
     * @param $userGameAttribute
     * @return UserGameAttribute
     */
    public function handlePlayed($userGameAttribute)
    {

        //Set the game to favorite or not
        if ($userGameAttribute && (get_class($userGameAttribute) === 'JDJ\CollectionBundle\Entity\UserGameAttribute')) {
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
            $userGameAttribute = new UserGameAttribute(
                false,
                false,
                false,
                true,
                $this->getUser()->getId(),
                $this->getJeu()->getId(),
                $this->getUser(),
                $this->getJeu()
            );

            $this->manager->record($userGameAttribute);
        }
        return $userGameAttribute;
    }

    /**
     * This function get the userGameAttribute
     *
     * @param $jeu_id
     * @param $user_id
     * @return mixed
     */
    public function getUserGameAttribute($jeu_id, $user_id)
    {

        //Gets user and game
        $this->jeu = $this->em->getRepository('JDJJeuBundle:Jeu')->find($jeu_id);
        $this->user = $this->em->getRepository('JDJUserBundle:User')->find($user_id);

        if (!$this->jeu || !$this->user) {
            throw $this->createNotFoundException('Unable to find Game or User entity.');
        }

        $userGameAttribute = $this->em->getRepository('JDJCollectionBundle:UserGameAttribute')->findUserGameAttribute($this->jeu, $this->user);

        return $userGameAttribute[0];
    }

    /**
     * @return Jeu
     */
    public function getJeu()
    {
        return $this->jeu;
    }

    /**
     * @param Jeu $jeu
     */
    public function setJeu($jeu)
    {
        $this->jeu = $jeu;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


}
