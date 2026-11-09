<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\Entity\ProductInterface;
use App\Entity\ProductReview;
use App\Updater\ReviewCountByProductUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class CalculateReviewCountByProductSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ReviewCountByProductUpdater $updater,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.product_review.post_create' => 'onProductCreate',
            'sylius.product_review.post_update' => 'onProductUpdate',
        ];
    }

    public function onProductCreate(GenericEvent $event): void
    {
        /** @var ProductReview $productReview */
        $productReview = $event->getSubject();
        /** @var ProductInterface $product */
        $product = $productReview->getReviewSubject();

        $this->updater->update($product);

        $this->entityManager->flush();
    }

    public function onProductUpdate(GenericEvent $event): void
    {
        $this->onProductCreate($event);
    }
}
