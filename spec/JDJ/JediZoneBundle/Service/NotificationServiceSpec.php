<?php

namespace spec\JDJ\JediZoneBundle\Service;

use Doctrine\ORM\EntityManager;
use JDJ\JediZoneBundle\Entity\Activity;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserBundle\Entity\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class NotificationServiceSpec extends ObjectBehavior
{
    function let(EntityManager $em)
    {
        $this->beConstructedWith($em, "JDJJediZoneBundle:Notification");
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

        //The user that changed the game status
        $hasher = $prophet->prophesize('JedizoneBundle\Entity\Activity');
        $activity = new Activity($hasher->reveal());

        $activity->addUser($user);

        //the function returns a notification
        $this
            ->createNotification($user, $activity)
            ->shouldReturnAnInstanceOf('JDJ\JedizoneBundle\Entity\Notification');

        //The user passed is the user of the notification
        $this
            ->createNotification($user, $activity)
            ->getUser()
            ->shouldReturn($user);

    }
}
