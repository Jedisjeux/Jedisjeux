<?php

namespace spec\JDJ\JediZoneBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use JDJ\JediZoneBundle\Entity\Activity;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserBundle\Entity\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;
use Symfony\Component\VarDumper\VarDumper;

class ActivityServiceSpec extends ObjectBehavior
{

    function let(EntityManager $em)
    {
        date_default_timezone_set('Europe/Istanbul');
        $this->beConstructedWith($em, "JDJJediZoneBundle:Activity");
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('JDJ\JediZoneBundle\Service\ActivityService');
    }

    function it_should_create_an_activity_of_a_game()
    {

        $prophet = new Prophet();

        //The game that status has changed
        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu = new Jeu($hasher->reveal());

        $jeu->setStatus(Jeu::NEED_A_TRANSLATION);

        //The user that changed the game status
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user = new User($hasher->reveal());

        //the function returns an activity
        $this
            ->createActivity($jeu, $user)
            ->shouldReturnAnInstanceOf('JDJ\JedizoneBundle\Entity\Activity');

        //The game of the activity is the one passed
        $this
            ->createActivity($jeu, $user)
            ->getJeu()
            ->shouldReturn($jeu);

        //The user is in the user of the activity
        $this
            ->createActivity($jeu, $user)
            ->getUsers()
            ->contains($user)
            ->shouldReturn(true);

    }

    function it_should_add_a_user_to_an_existing_activity()
    {
        $prophet = new Prophet();

        //The user that changed the game status
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user = new User($hasher->reveal());

        //The user that changed the game status
        $hasher = $prophet->prophesize('JedizoneBundle\Entity\Activity');
        $activity = new Activity($hasher->reveal());

        $activity->addUser($user);

        //the function returns an activity
        $this
            ->addUserActivity($activity, $user)
            ->shouldReturnAnInstanceOf('JDJ\JedizoneBundle\Entity\Activity');

        //The user is in the user of the activity
        $this
            ->addUserActivity($activity, $user)
            ->getUsers()
            ->contains($user)
            ->shouldReturn(true);

    }
}
