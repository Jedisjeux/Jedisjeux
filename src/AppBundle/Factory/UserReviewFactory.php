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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class UserReviewFactory extends Factory
{
    /**
     * @param TokenStorage $tokenStorage
     * @param string $slug
     * @param EntityRepository $gameRepository
     *
     * @return UserReview
     */
    public function createNewWithCurrentUserAndGame(TokenStorage $tokenStorage, $slug, EntityRepository $gameRepository)
    {
        /** @var UserReview $userReview */
        $userReview =  parent::createNew();

        /** @var Jeu $game */
        $game = $gameRepository->findOneBy(array('slug' => $slug));

        if (null === $game) {
            throw new NotFoundHttpException(sprintf('Game with slug %s not found', $slug));
        }

        $userReview
            ->getJeuNote()
            ->setJeu($game)
            ->setAuthor($tokenStorage->getToken()->getUser());

        return $userReview;
    }
}