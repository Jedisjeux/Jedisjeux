<?php

namespace JDJ\JediZoneBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use JDJ\JediZoneBundle\Entity\Activity;
use JDJ\JediZoneBundle\Repository\ActivityRepository;
use JDJ\UserBundle\Entity\User;
use Sylius\Component\Product\Model\ProductInterface;


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
     * @var ActivityRepository
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
     * @param ProductInterface $jeu
     *
     * @return Activity
     */
    public function getActivity(ProductInterface $jeu, User $user)
    {

        $activity = $jeu->getActivity();

        //Checks if the activity exists
        if (!$activity) {
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
     * @param ProductInterface $jeu
     * @param User $user
     *
     * @return Activity
     */
    public function createActivity(ProductInterface $jeu, User $user)
    {
        //Create the new activity
        $activity = new Activity();

        //Sets the params of the activity
        $activity
            ->setJeu($jeu)
            ->setPublished(new \DateTime())
            ->setActionUser($user)
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

        //Sets the action user
        $activity->setActionUser($user);

        //Checks if user not in activity
        if (!$activity->getUsers()->contains($user)) {
            $activity->addUser($user);
        }

        return $activity;
    }

    /**
     * This function gets the activities of the user
     *
     * @param User $user
     * @return array
     */
    public function getActivitiesFromUser(User $user)
    {
        return $this
            ->repo
            ->findBy(array(
                "user" => $user
            ));
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
