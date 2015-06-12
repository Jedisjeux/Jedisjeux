<?php

namespace spec\JDJ\JediZoneBundle\Service;

use Doctrine\ORM\EntityManager;
use JDJ\JediZoneBundle\Entity\Activity;
use JDJ\JediZoneBundle\Entity\Notification;
use JDJ\JediZoneBundle\Service\ActivityService;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserBundle\Entity\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class NotificationServiceSpec extends ObjectBehavior
{
    function let(EntityManager $em, ActivityService $activityService)
    {
        $this->beConstructedWith($em, $activityService, "JDJJediZoneBundle:Notification");
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('JDJ\JediZoneBundle\Service\NotificationService');
    }


    function it_should_create_a_notification_for_an_activity()
    {

        $prophet = new Prophet();

        //The user that changed the game status
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user = new User($hasher->reveal());

        //The game concerned
        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu = new Jeu($hasher->reveal());

        $jeu->setStatus(Jeu::NEED_A_REVIEW);

        //The user that changed the game status
        $hasher = $prophet->prophesize('JedizoneBundle\Entity\Activity');
        $activity = new Activity($hasher->reveal());

        $activity
            ->addUser($user)
            ->setJeu($jeu)
        ;

        //the function returns a notification
        $this
            ->createNotification($user, $activity, Notification::ACTION_ACCEPT, "")
            ->shouldReturnAnInstanceOf('JDJ\JedizoneBundle\Entity\Notification');

        //The user passed is the user of the notification
        $this
            ->createNotification($user, $activity, Notification::ACTION_ACCEPT, "")
            ->getUser()
            ->shouldReturn($user);

    }


    function it_should_get_the_role_associated_to_a_game_status()
    {
        $prophet = new Prophet();

        //The user that changed the game status
        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu = new Jeu($hasher->reveal());

        $jeu->setStatus(Jeu::NEED_A_REVIEW);
        $this
            ->getConcernedRoleFromGameStatus($jeu)
            ->shouldReturn('ROLE_REVIEWER');

        $jeu->setStatus(Jeu::WRITING);
        $this
            ->getConcernedRoleFromGameStatus($jeu)
            ->shouldReturn('ROLE_REDACTOR');

        $jeu->setStatus(Jeu::NEED_A_TRANSLATION);
        $this
            ->getConcernedRoleFromGameStatus($jeu)
            ->shouldReturn('ROLE_TRANSLATOR');

        $jeu->setStatus(Jeu::READY_TO_PUBLISH);
        $this
            ->getConcernedRoleFromGameStatus($jeu)
            ->shouldReturn('ROLE_PUBLISHER');
    }


    function it_should_add_notifications_to_an_activity()
    {
        $prophet = new Prophet();

        $hasher = $prophet->prophesize('JedizoneBundle\Entity\Activity');
        $activity = new Activity($hasher->reveal());

        //The user that changed the game status
        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu = new Jeu($hasher->reveal());

        $activity->setJeu($jeu);

        $this->createNotifications($activity,  Notification::ACTION_ACCEPT, "")->shouldReturn($activity);
    }
}
