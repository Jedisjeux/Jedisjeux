<?php

namespace JDJ\JediZoneBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use JDJ\JediZoneBundle\Entity\Activity;
use JDJ\JediZoneBundle\Entity\Notification;
use JDJ\UserBundle\Entity\User;


/**
 * Class NotificationService
 * @package JDJ\JediZoneBundle\Service
 */
class NotificationService
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
     * This function create all the notifications when there is a game status change
     *
     * @param Activity $activity
     *
     * @return Activity
     */
    public function createNotifications(Activity $activity)
    {
        $tabUser = $activity->getUsers();

        /**
         * for the user of the activity
         * Create a notification
         */
        if(!empty($tabUser)){
            foreach($tabUser as $user) {

                //Create the notification
                $notification = $this->createNotification($user, $activity);

                //persist the notification
                $this->repo->saveNotification($notification);

                //Add the notification to the activity
                $activity->addNotification($notification);

            }
        }

        /**
         * Create notification for the workflow dudes that work at the new status of the game
         */

        //TODO

        //persist the activity
        $this->em->persist($activity);
        $this->em->flush();

        return $activity;
    }

    /**
     * This function will return a created Notification
     *
     * @param User $user
     * @param Activity $activity
     *
     * @return Notification
     */
    public function createNotification(User $user, Activity $activity)
    {
        $notification = new Notification();

        //Sets the params of the notification
        $notification
            ->setActivity($activity)
            ->setIsRead(false)
            ->setUser($user)
            ->setAction(Notification::ACTION_ACCEPT)
            ->setComment("");

        return $notification;
    }


}
