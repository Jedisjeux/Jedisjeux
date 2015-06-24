<?php

namespace JDJ\JediZoneBundle\Listener;

use JDJ\JediZoneBundle\Service\ActivityService;
use JDJ\JediZoneBundle\Service\NotificationService;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Listener notification creation
 *
 * Class NotificationListener
 * @package JDJ\JediZoneBundle
 */
class NotificationListener
{
    /**
     * @var NotificationService
     */
    protected $notificationService;

    /**
     * @var ActivityService
     */
    protected $activityService;

    /**
     * @var JDJ\JeuBundle\Entity\Jeu
     */
    protected $jeu;

    /**
     * @var JDJ\UserBundle\Entity\User
     */
    protected $user;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $comment;

    /**
     * Constructor
     *
     * @param NotificationService $notificationService
     * @param ActivityService $activityService
     * @param Jeu $jeu
     * @param User $user
     */
    public function __construct(NotificationService $notificationService,ActivityService $activityService, Jeu $jeu, User $user, $action, $comment)
    {
        $this->notificationService = $notificationService;
        $this->activityService = $activityService;
        $this->jeu  = $jeu;
        $this->user = $user;
        $this->action = $action;
        $this->comment = $comment;
    }

    /**
     * Notification creation for all the workflow people concerned
     */
    public function updateActivity(FilterResponseEvent $event)
    {

        if (!$event->isMasterRequest()) {
            return;
        }

        //Get the activity of the game
        $activity = $this->activityService->getActivity($this->jeu, $this->user);

        //Create the notifications
        $this->notificationService->createNotifications($activity, $this->action, $this->comment);

    }
}