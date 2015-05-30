<?php

namespace JDJ\JediZoneBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use JDJ\JediZoneBundle\Entity\Activity;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserBundle\Entity\User;
use Symfony\Component\VarDumper\VarDumper;


/**
 * Class ActivityService
 * @package JDJ\JediZoneBundle\Service
 */
class ActivityService
{

    /**
     * Holds the Doctrine entity manager for database interaction
     * @var EntityManager
     */
    protected $em;

    /**
     * Entity-specific repo, useful for finding entities, for example
     * @var JeuRepository
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
     * This function check for the activity of the game
     * create the activity if doesn't exist
     * add the user if isn't following
     *
     * @param Jeu $jeu
     *
     * @return Activity
     */
    public function getActivity(Jeu $jeu, User $user)
    {

        $activity = $jeu->getActivity();

        //Checks if the activity exists
        if(!$activity)
        {
            //Create a new activity
            $activity = $this->createActivity($jeu, $user);
        } else {
            //Adds the user to the existing activity
            $this->addUserActivity($activity, $user);
        }

        //Save the activity to the database
        $this->saveActivity($activity);

        return $activity;
    }

    /**
     * This function will return a created activity
     *
     * @param Jeu $jeu
     * @param User $user
     *
     * @return Activity
     */
    public function createActivity(Jeu $jeu, User $user)
    {
        //Create the new activity
        $activity = new Activity();

        //Sets the params of the activity
        $activity
            ->setJeu($jeu)
            ->setPublished(new \DateTime())
            ->addUser($user);

        return $activity;
    }


    /**
     * This function add the user to the activity if not already in it
     *
     * @param Activity $activity
     * @param User $user
     *
     * @return Activity
     */
    public function addUserActivity(Activity $activity, User $user)
    {

        //Checks if user not in activity
        if(!$activity->getUsers()->contains($user)) {
            $activity->addUser($user);
        }

        return $activity;
    }

    /**
     * Save the activity to the database
     *
     * @param $activity
     */
    public function saveActivity($activity)
    {
        $this->repo->saveActivity($activity);
    }
}
