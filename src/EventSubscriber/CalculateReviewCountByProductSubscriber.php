<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
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
    /** @var ReviewCountByProductUpdater */
    private $updater;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(ReviewCountByProductUpdater $updater, EntityManagerInterface $entityManager)
    {
        $this->updater = $updater;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'sylius.product_review.post_create' => 'onProductCreate',
            'sylius.product_review.post_update' => 'onProductUpdate',
        ];
    }

    public function onProductCreate(GenericEvent $event)
    {
        /** @var ProductReview $productReview */
        $productReview = $event->getSubject();
        /** @var ProductInterface $product */
        $product = $productReview->getReviewSubject();

        $this->updater->update($product);

        $this->entityManager->flush();
    }

    public function onProductUpdate(GenericEvent $event)
    {
        $this->onProductCreate($event);
    }
}
