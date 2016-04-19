<?php

namespace spec\JDJ\CollectionBundle\Service;

use Doctrine\ORM\EntityManager;
use JDJ\CollectionBundle\Entity\Collection;
use JDJ\JeuBundle\Entity\Jeu;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class CollectionServiceSpec extends ObjectBehavior
{
    function let(EntityManager $em)
    {
        $this->beConstructedWith($em, "JDJCollectionBundle:Collection");
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('JDJ\CollectionBundle\Service\CollectionService');
    }

    function it_creates_a_new_collection()
    {
        $prophet = new Prophet();
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user   = new User($hasher->reveal());

        $this->createCollection($user, "test", "test")->shouldReturnAnInstanceOf('JDJ\CollectionBundle\Entity\Collection');
    }

    function it_adds_a_game_to_a_collection()
    {
        $prophet = new Prophet();
        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu  = new Jeu($hasher->reveal());

        $hasher = $prophet->prophesize('JDJCollection\Entity\Collection');
        $collection  = new Collection($hasher->reveal());

        $this->addGameCollection($jeu, $collection)->getListElements()->shouldHaveCount(1);
    }



}
