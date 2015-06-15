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
     * @param $action
     * @param $comment
     *
     * @return Activity
     */
    public function createNotifications(Activity $activity, $action, $comment)
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
                $notification = $this->createNotification($user, $activity, $action, $comment);

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
     * @param $action
     * @param $comment
     *
     * @return Notification
     */
    public function createNotification(User $user, Activity $activity, $action, $comment)
    {
        $notification = new Notification();

        //Sets the params of the notification
        $notification
            ->setActivity($activity)
            ->setRead(false)
            ->setUser($user)
            ->setChangeStatus($activity->getJeu()->getStatus())
            ->setAction($action)
            ->setComment($comment);

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
     * @param null $status
     * @param null $noticationType
     * @return array
     */
    public function getNotificationsFromUser(User $user, $status = null, $noticationType = null)
    {
        $notifications = $this
            ->repo
            ->getNotificationsFromCriteria($user, $status, $noticationType);

        //Sets the notifications to read
        foreach ($notifications as $notification) {
            if (false === $notification->isRead()) {
                $this->setReadNotification($notification);
                $notification->setDisplayNew(true);
            } else {
                $notification->setDisplayNew(false);
            }
        }

        return $notifications;
    }

    /**
     * This function gets the notification counts for the filters display
     *
     * @param $user
     * @return array
     */
    public function getNotificationsFromUserCount($user)
    {
        $tabCountNotication = array();
        foreach (Jeu::getStatusList() as $status) {
            $notifications = $this
                ->repo
                ->getNotificationsFromCriteria($user, $status);

            if (count($notifications) > 0) {
                $tabCountNotication[$status] = count($notifications);
            }
        }

        return $tabCountNotication;
    }

    /**
     * This function sets the notification to read
     *
     * @param Notification $notification
     */
    public function setReadNotification(Notification $notification)
    {
        /**
         * Sets the notification to read and record it
         */
        $notification->setRead(true);
        $this
            ->repo
            ->saveNotification($notification);

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
