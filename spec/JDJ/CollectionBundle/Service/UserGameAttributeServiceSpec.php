<?php

namespace spec\JDJ\CollectionBundle\Service;

use Doctrine\ORM\EntityManager;
use JDJ\CollectionBundle\Entity\UserProductAttribute;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserBundle\Entity\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class UserGameAttributeServiceSpec extends ObjectBehavior
{
    function let(EntityManager $em)
    {
        $this->beConstructedWith($em, "JDJCollectionBundle:UserGameAttribute");
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('JDJ\CollectionBundle\Service\UserGameAttributeService');
    }

    function it_should_put_an_existing_game_to_user_favorite()
    {

        $prophet = new Prophet();
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user   = new User($hasher->reveal());

        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu   = new Jeu($hasher->reveal());

        $userGameAttribute = new UserProductAttribute();
        $userGameAttribute->setUser($user);
        $userGameAttribute->setJeu($jeu);

        $this->handleFavorite($userGameAttribute,$jeu, $user)->isFavorite()->shouldReturn(true);

    }

    function it_should_put_an_existing_game_to_user_non_favorite()
    {

        $prophet = new Prophet();
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user   = new User($hasher->reveal());

        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu   = new Jeu($hasher->reveal());

        $userGameAttribute = new UserProductAttribute();
        $userGameAttribute->setFavorite(true);
        $userGameAttribute->setUser($user);
        $userGameAttribute->setJeu($jeu);


        $this->handleFavorite($userGameAttribute,$jeu, $user)->isFavorite()->shouldReturn(false);
    }

    function it_should_put_an_non_existing_game_to_user_favorite()
    {
        $prophet = new Prophet();
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user   = new User($hasher->reveal());

        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu   = new Jeu($hasher->reveal());

        $userGameAttribute = null;

        $this->handleFavorite($userGameAttribute,$jeu, $user)->isFavorite()->shouldReturn(true);
    }

    function it_should_put_an_existing_game_to_user_owned_game()
    {

        $prophet = new Prophet();
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user   = new User($hasher->reveal());

        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu   = new Jeu($hasher->reveal());

        $userGameAttribute = new UserProductAttribute();
        $userGameAttribute->setUser($user);
        $userGameAttribute->setJeu($jeu);

        $this->handleOwned($userGameAttribute,$jeu, $user)->isOwned()->shouldReturn(true);

    }

    function it_should_put_an_existing_game_to_user_non_owned_game()
    {
        $prophet = new Prophet();
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user   = new User($hasher->reveal());

        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu   = new Jeu($hasher->reveal());

        $userGameAttribute = new UserProductAttribute();
        $userGameAttribute->setOwned(true);
        $userGameAttribute->setUser($user);
        $userGameAttribute->setJeu($jeu);


        $this->handleOwned($userGameAttribute,$jeu, $user)->isOwned()->shouldReturn(false);
    }

    function it_should_put_an_non_existing_game_to_user_owned_game()
    {
        $prophet = new Prophet();
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user   = new User($hasher->reveal());

        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu   = new Jeu($hasher->reveal());

        $userGameAttribute = null;

        $this->handleOwned($userGameAttribute,$jeu, $user)->isOwned()->shouldReturn(true);
    }

    function it_should_put_an_existing_game_to_user_wanted_game()
    {

        $prophet = new Prophet();
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user   = new User($hasher->reveal());

        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu   = new Jeu($hasher->reveal());

        $userGameAttribute = new UserProductAttribute();
        $userGameAttribute->setUser($user);
        $userGameAttribute->setJeu($jeu);

        $this->handleWanted($userGameAttribute,$jeu, $user)->isWanted()->shouldReturn(true);

    }

    function it_should_put_an_existing_game_to_user_non_wanted_game()
    {
        $prophet = new Prophet();
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user   = new User($hasher->reveal());

        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu   = new Jeu($hasher->reveal());

        $userGameAttribute = new UserProductAttribute();
        $userGameAttribute->setWanted(true);
        $userGameAttribute->setUser($user);
        $userGameAttribute->setJeu($jeu);

        $this->handleWanted($userGameAttribute, $jeu, $user)->isWanted()->shouldReturn(false);
    }

    function it_should_put_an_non_existing_game_to_user_wanted_game()
    {
        $prophet = new Prophet();
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user   = new User($hasher->reveal());

        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu   = new Jeu($hasher->reveal());

        $userGameAttribute = null;

        $this->handleWanted($userGameAttribute,$jeu, $user)->isWanted()->shouldReturn(true);
    }

    function it_should_put_an_existing_game_to_user_played_game()
    {

        $prophet = new Prophet();
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user   = new User($hasher->reveal());

        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu   = new Jeu($hasher->reveal());

        $userGameAttribute = new UserProductAttribute();
        $userGameAttribute->setUser($user);
        $userGameAttribute->setJeu($jeu);

        $this->handlePlayed($userGameAttribute,$jeu, $user)->hasPlayed()->shouldReturn(true);

    }

    function it_should_put_an_existing_game_to_user_non_played_game()
    {
        $prophet = new Prophet();
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user   = new User($hasher->reveal());

        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu   = new Jeu($hasher->reveal());

        $userGameAttribute = new UserProductAttribute();
        $userGameAttribute->setPlayed(true);
        $userGameAttribute->setUser($user);
        $userGameAttribute->setJeu($jeu);

        $this->handlePlayed($userGameAttribute, $jeu, $user)->hasPlayed()->shouldReturn(false);
    }

    function it_should_put_an_non_existing_game_to_user_played_game()
    {
        $prophet = new Prophet();
        $hasher = $prophet->prophesize('UserBundle\Entity\User');
        $user   = new User($hasher->reveal());

        $hasher = $prophet->prophesize('JeuBundle\Entity\Jeu');
        $jeu   = new Jeu($hasher->reveal());

        $userGameAttribute = null;

        $this->handlePlayed($userGameAttribute, $jeu, $user)->hasPlayed()->shouldReturn(true);
    }
}
