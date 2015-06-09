<?php

namespace JDJ\JediZoneBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use JDJ\JediZoneBundle\Entity\Activity;
use JDJ\JediZoneBundle\Entity\Notification;
use JDJ\JediZoneBundle\Repository\NotificationRepository;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserBundle\Entity\User;
use Symfony\Component\VarDumper\VarDumper;


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
     * @var NotificationRepository
     */
    protected $repo;

    /**
     * @var ActivityService
     */
    protected $activityService;

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
    public function __construct(EntityManager $em, ActivityService $activityService, $class)
    {
        $this->em = $em;
        $this->class = $class;
        $this->activityService = $activityService;
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
        //Notifications for the users of the activity
        $tabUser = $activity->getUsers();
        //Notifications for the workflow users that have the necessary role
        $tabRoleUser = $this->getUserJoiningActivity($activity);
        if (!empty($tabRoleUser)) {
            foreach ($tabRoleUser as $user) {
                if (!$tabUser->contains($user)) {
                    $tabUser->add($user);
                }
            }
        }

        /**
         * Create the notifications
         */
        if (!empty($tabUser)) {
            foreach ($tabUser as $user) {

                //Create the notification
                $notification = $this->createNotification($user, $activity);

                //persist the notification
                $this->repo->saveNotification($notification);

                //Add the notification to the activity
                $activity->addNotification($notification);

            }
        }

        //persist the activity
        $this
            ->activityService
            ->saveActivity($activity);

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
            ->setChangeStatus($activity->getJeu()->getStatus())
            ->setAction(Notification::ACTION_ACCEPT)
            ->setComment("");

        return $notification;
    }


    /**
     * This function gets all the user that are concerned by the new status of the game
     *
     * @param $activity
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserJoiningActivity($activity)
    {
        $role = $this->getConcernedRoleFromGameStatus($activity->getJeu());

        $tabUser = null;
        if ($role) {
            $tabUser = $this
                ->em
                ->getRepository('JDJUserBundle:User')
                ->findByRole($role);
        }

        return $tabUser;
    }


    /**
     * This function gets the notifications of the user
     *
     * @param User $user
     * @return array
     */
    public function getNotificationFromUser(User $user)
    {
        $notifications = $this
            ->repo
            ->findBy(array(
                "user" => $user
            ), array(
                'id' => 'desc'
            ));

        //Sets the notifications to read
        /*foreach ($notifications as $notification) {
            if (0 === $notification->isRead()) {

            }
        }*/

        return $notifications;
    }

    //TODO finir
    public function setReadNotification(Notification $notification) {
        $notification->setRead(1);

        $this
            ->repo
            ->saveNotification($notification);

        return $notification;
    }

    /**
     * This function returns the concerned role for the new status of a game
     *
     * @param Jeu $jeu
     * @return string
     */
    public function getConcernedRoleFromGameStatus(Jeu $jeu)
    {
        $role = null;
        switch ($jeu->getStatus()) {
            case Jeu::NEED_A_REVIEW :
                $role = 'ROLE_REVIEWER';
                break;
            case Jeu::WRITING :
                $role = 'ROLE_REDACTOR';
                break;
            case Jeu::NEED_A_TRANSLATION :
                $role = 'ROLE_TRANSLATOR';
                break;
            case Jeu::READY_TO_PUBLISH :
                $role = 'ROLE_PUBLISHER';
                break;
        }

        return $role;
    }


}
