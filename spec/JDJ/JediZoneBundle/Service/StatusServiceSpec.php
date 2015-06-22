<?php

namespace spec\JDJ\JediZoneBundle\Service;

use Doctrine\ORM\EntityManager;
use JDJ\JeuBundle\Entity\Jeu;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class StatusServiceSpec extends ObjectBehavior
{

    function let(EntityManager $em)
    {
        $this->beConstructedWith($em, "JDJJeuBundle:Jeu");
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('JDJ\JediZoneBundle\Service\StatusService');
    }

    function it_should_put_a_game_to_a_different_status()
    {

        $prophet = new Prophet();

        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu   = new Jeu($hasher->reveal());

        $jeu->setStatus(Jeu::NEED_A_TRANSLATION);

        $this->changeGameStatus($jeu, Jeu::NEED_A_REVIEW)->getStatus()->shouldReturn(Jeu::NEED_A_REVIEW);
    }
}
