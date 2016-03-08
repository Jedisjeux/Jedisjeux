<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 15/02/2016
 * Time: 13:14
 */

namespace AppBundle\Factory;

use AppBundle\Entity\GameReview;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GameReviewFactory extends Factory
{
    /**
     * @param TokenStorage $tokenStorage
     * @param string $slug
     * @param EntityRepository $productRepository
     *
     * @return GameReview
     */
    public function createNewWithCurrentUserAndGame(TokenStorage $tokenStorage, $slug, EntityRepository $productRepository)
    {
        /** @var GameReview $gameReview */
        $gameReview =  parent::createNew();

        /** @var ProductInterface $game */
        $game = $productRepository->findOneBy(array('slug' => $slug));

        if (null === $game) {
            throw new NotFoundHttpException(sprintf('Product with slug %s not found', $slug));
        }

        $gameReview
            ->getRate()
            ->setGame($game)
            ->setCreatedBy($tokenStorage->getToken()->getUser());

        return $gameReview;
    }
}