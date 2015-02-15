<?php

namespace spec\JDJ\CollectionBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JDJ\CollectionBundle\Entity\UserGameAttribute;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserBundle\Entity\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserGameAttributeServiceSpec extends ObjectBehavior
{
    function let(EntityManager $em)
    {
        $this->beConstructedWith($em);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('JDJ\CollectionBundle\Service\UserGameAttributeService');
    }

    function it_should_put_an_existing_game_to_user_favorite(
        EntityManager $entityManager,
        User $user,
        Jeu $jeu,
        EntityRepository $jeuRepository,
        EntityRepository $userRepository
    )
    {

        $jeu_id = 1;
        $user_id = 5;
        $entityManager->getRepository(Argument::exact('JDJJeuBundle:Jeu'))->willReturn($jeuRepository);
        $entityManager->getRepository(Argument::exact('JDJUserBundle:User'))->willReturn($userRepository);
        $jeuRepository->find($jeu_id)->willReturn($jeu);
        $userRepository->find($user_id)->willReturn($user);

        $userGameAttribute = new UserGameAttribute(
            false,
            false,
            false,
            false,
            $user->getId(),
            $jeu->getId(),
            $user,
            $jeu
        );

        $this->handleFavorite($userGameAttribute)->isFavorite()->shouldReturn(true);

    }

    function it_should_put_an_existing_game_to_user_non_favorite(
        EntityManager $entityManager,
        User $user,
        Jeu $jeu,
        EntityRepository $jeuRepository,
        EntityRepository $userRepository
    )
    {
        $jeu_id = 1;
        $user_id = 5;
        $entityManager->getRepository(Argument::exact('JDJJeuBundle:Jeu'))->willReturn($jeuRepository);
        $entityManager->getRepository(Argument::exact('JDJUserBundle:User'))->willReturn($userRepository);
        $jeuRepository->find($jeu_id)->willReturn($jeu);
        $userRepository->find($user_id)->willReturn($user);

        $userGameAttribute = new UserGameAttribute(
            true,
            false,
            false,
            false,
            $user->getId(),
            $jeu->getId(),
            $user,
            $jeu
        );

        $this->handleFavorite($userGameAttribute)->isFavorite()->shouldReturn(false);
    }

    function it_should_put_an_non_existing_game_to_user_favorite(
        EntityManager $entityManager,
        User $user,
        Jeu $jeu,
        EntityRepository $jeuRepository,
        EntityRepository $userRepository
    )
    {
        $jeu_id = 1;
        $user_id = 5;
        $entityManager->getRepository(Argument::exact('JDJJeuBundle:Jeu'))->willReturn($jeuRepository);
        $entityManager->getRepository(Argument::exact('JDJUserBundle:User'))->willReturn($userRepository);
        $jeuRepository->find($jeu_id)->willReturn($jeu);
        $userRepository->find($user_id)->willReturn($user);
        $this->setJeu($jeu);
        $this->setUser($user);

        $userGameAttribute = null;

        $this->handleFavorite($userGameAttribute)->isFavorite()->shouldReturn(true);
    }

    function it_should_put_an_existing_game_to_user_owned_game(
        EntityManager $entityManager,
        User $user,
        Jeu $jeu,
        EntityRepository $jeuRepository,
        EntityRepository $userRepository
    )
    {

        $jeu_id = 1;
        $user_id = 5;
        $entityManager->getRepository(Argument::exact('JDJJeuBundle:Jeu'))->willReturn($jeuRepository);
        $entityManager->getRepository(Argument::exact('JDJUserBundle:User'))->willReturn($userRepository);
        $jeuRepository->find($jeu_id)->willReturn($jeu);
        $userRepository->find($user_id)->willReturn($user);

        $userGameAttribute = new UserGameAttribute(
            false,
            false,
            false,
            false,
            $user->getId(),
            $jeu->getId(),
            $user,
            $jeu
        );

        $this->handleOwned($userGameAttribute)->isOwned()->shouldReturn(true);

    }

    function it_should_put_an_existing_game_to_user_non_owned_game(
        EntityManager $entityManager,
        User $user,
        Jeu $jeu,
        EntityRepository $jeuRepository,
        EntityRepository $userRepository
    )
    {
        $jeu_id = 1;
        $user_id = 5;
        $entityManager->getRepository(Argument::exact('JDJJeuBundle:Jeu'))->willReturn($jeuRepository);
        $entityManager->getRepository(Argument::exact('JDJUserBundle:User'))->willReturn($userRepository);
        $jeuRepository->find($jeu_id)->willReturn($jeu);
        $userRepository->find($user_id)->willReturn($user);

        $userGameAttribute = new UserGameAttribute(
            false,
            true,
            false,
            false,
            $user->getId(),
            $jeu->getId(),
            $user,
            $jeu
        );

        $this->handleOwned($userGameAttribute)->isOwned()->shouldReturn(false);
    }

    function it_should_put_an_non_existing_game_to_user_owned_game(
        EntityManager $entityManager,
        User $user,
        Jeu $jeu,
        EntityRepository $jeuRepository,
        EntityRepository $userRepository
    )
    {
        $jeu_id = 1;
        $user_id = 5;
        $entityManager->getRepository(Argument::exact('JDJJeuBundle:Jeu'))->willReturn($jeuRepository);
        $entityManager->getRepository(Argument::exact('JDJUserBundle:User'))->willReturn($userRepository);
        $jeuRepository->find($jeu_id)->willReturn($jeu);
        $userRepository->find($user_id)->willReturn($user);
        $this->setJeu($jeu);
        $this->setUser($user);

        $userGameAttribute = null;

        $this->handleOwned($userGameAttribute)->isOwned()->shouldReturn(true);
    }

    function it_should_put_an_existing_game_to_user_wanted_game(
        EntityManager $entityManager,
        User $user,
        Jeu $jeu,
        EntityRepository $jeuRepository,
        EntityRepository $userRepository
    )
    {

        $jeu_id = 1;
        $user_id = 5;
        $entityManager->getRepository(Argument::exact('JDJJeuBundle:Jeu'))->willReturn($jeuRepository);
        $entityManager->getRepository(Argument::exact('JDJUserBundle:User'))->willReturn($userRepository);
        $jeuRepository->find($jeu_id)->willReturn($jeu);
        $userRepository->find($user_id)->willReturn($user);

        $userGameAttribute = new UserGameAttribute(
            false,
            false,
            false,
            false,
            $user->getId(),
            $jeu->getId(),
            $user,
            $jeu
        );

        $this->handleWanted($userGameAttribute)->isWanted()->shouldReturn(true);

    }

    function it_should_put_an_existing_game_to_user_non_wanted_game(
        EntityManager $entityManager,
        User $user,
        Jeu $jeu,
        EntityRepository $jeuRepository,
        EntityRepository $userRepository
    )
    {
        $jeu_id = 1;
        $user_id = 5;
        $entityManager->getRepository(Argument::exact('JDJJeuBundle:Jeu'))->willReturn($jeuRepository);
        $entityManager->getRepository(Argument::exact('JDJUserBundle:User'))->willReturn($userRepository);
        $jeuRepository->find($jeu_id)->willReturn($jeu);
        $userRepository->find($user_id)->willReturn($user);

        $userGameAttribute = new UserGameAttribute(
            false,
            false,
            true,
            false,
            $user->getId(),
            $jeu->getId(),
            $user,
            $jeu
        );

        $this->handleWanted($userGameAttribute, $jeu, $user)->isWanted()->shouldReturn(false);
    }

    function it_should_put_an_non_existing_game_to_user_wanted_game(
        EntityManager $entityManager,
        User $user,
        Jeu $jeu,
        EntityRepository $jeuRepository,
        EntityRepository $userRepository
    )
    {
        $jeu_id = 1;
        $user_id = 5;
        $entityManager->getRepository(Argument::exact('JDJJeuBundle:Jeu'))->willReturn($jeuRepository);
        $entityManager->getRepository(Argument::exact('JDJUserBundle:User'))->willReturn($userRepository);
        $jeuRepository->find($jeu_id)->willReturn($jeu);
        $userRepository->find($user_id)->willReturn($user);
        $this->setJeu($jeu);
        $this->setUser($user);

        $userGameAttribute = null;

        $this->handleWanted($userGameAttribute)->isWanted()->shouldReturn(true);
    }

    function it_should_put_an_existing_game_to_user_played_game(
        EntityManager $entityManager,
        User $user,
        Jeu $jeu,
        EntityRepository $jeuRepository,
        EntityRepository $userRepository
    )
    {

        $jeu_id = 1;
        $user_id = 5;
        $entityManager->getRepository(Argument::exact('JDJJeuBundle:Jeu'))->willReturn($jeuRepository);
        $entityManager->getRepository(Argument::exact('JDJUserBundle:User'))->willReturn($userRepository);
        $jeuRepository->find($jeu_id)->willReturn($jeu);
        $userRepository->find($user_id)->willReturn($user);

        $userGameAttribute = new UserGameAttribute(
            false,
            false,
            false,
            false,
            $user->getId(),
            $jeu->getId(),
            $user,
            $jeu
        );

        $this->handlePlayed($userGameAttribute)->hasPlayed()->shouldReturn(true);

    }

    function it_should_put_an_existing_game_to_user_non_played_game(
        EntityManager $entityManager,
        User $user,
        Jeu $jeu,
        EntityRepository $jeuRepository,
        EntityRepository $userRepository
    )
    {
        $jeu_id = 1;
        $user_id = 5;
        $entityManager->getRepository(Argument::exact('JDJJeuBundle:Jeu'))->willReturn($jeuRepository);
        $entityManager->getRepository(Argument::exact('JDJUserBundle:User'))->willReturn($userRepository);
        $jeuRepository->find($jeu_id)->willReturn($jeu);
        $userRepository->find($user_id)->willReturn($user);

        $userGameAttribute = new UserGameAttribute(
            false,
            false,
            false,
            true,
            $user->getId(),
            $jeu->getId(),
            $user,
            $jeu
        );

        $this->handlePlayed($userGameAttribute, $jeu, $user)->hasPlayed()->shouldReturn(false);
    }

    function it_should_put_an_non_existing_game_to_user_played_game(
        EntityManager $entityManager,
        User $user,
        Jeu $jeu,
        EntityRepository $jeuRepository,
        EntityRepository $userRepository
    )
    {
        $jeu_id = 1;
        $user_id = 5;
        $entityManager->getRepository(Argument::exact('JDJJeuBundle:Jeu'))->willReturn($jeuRepository);
        $entityManager->getRepository(Argument::exact('JDJUserBundle:User'))->willReturn($userRepository);
        $jeuRepository->find($jeu_id)->willReturn($jeu);
        $userRepository->find($user_id)->willReturn($user);
        $this->setJeu($jeu);
        $this->setUser($user);

        $userGameAttribute = null;

        $this->handlePlayed($userGameAttribute, $jeu, $user)->hasPlayed()->shouldReturn(true);
    }
}
