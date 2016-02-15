<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 15/02/2016
 * Time: 13:14
 */

namespace AppBundle\Factory;

use Doctrine\ORM\EntityRepository;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserBundle\Entity\User;
use JDJ\UserReviewBundle\Entity\UserReview;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class UserReviewFactory extends Factory
{
    /**
     * @var EntityRepository
     */
    protected $userRepository;

    /**
     * @var EntityRepository
     */
    protected $gameRepository;

    /**
     * @param EntityRepository $userRepository
     */
    public function setUserRepository($userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param EntityRepository $gameRepository
     */
    public function setGameRepository($gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param int $userId
     * @param string $slug
     * @return UserReview
     */
    public function createNewWithUserAndGame($userId, $slug)
    {
        /** @var UserReview $userReview */
        $userReview =  parent::createNew();

        /** @var User $user */
        $user = $this->userRepository->find($userId);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('User with id %s not found', $userId));
        }

        /** @var Jeu $game */
        $game = $this->gameRepository->findOneBy(array('slug' => $slug));

        if (null === $game) {
            throw new NotFoundHttpException(sprintf('Game with slug %s not found', $slug));
        }

        $userReview
            ->getJeuNote()
            ->setJeu($game)
            ->setAuthor($user);

        return $userReview;
    }
}