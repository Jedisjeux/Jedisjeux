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

use App\Entity\Product;
use App\Event\ProductEvents;
use App\Updater\ReviewCountByProductUpdater;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class CalculateReviewCountByProductSubscriber implements EventSubscriberInterface
{
    /**
     * @var ReviewCountByProductUpdater
     */
    protected $updater;

    /**
     * @param ReviewCountByProductUpdater $updater
     */
    public function __construct(ReviewCountByProductUpdater $updater)
    {
        $this->updater = $updater;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ProductEvents::PRE_CREATE => 'onProductCreate',
            ProductEvents::PRE_UPDATE => 'onProductUpdate',
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function onProductCreate(GenericEvent $event)
    {
        /** @var Product $product */
        $product = $event->getSubject();

        $this->updater->update($product);
    }

    /**
     * @param GenericEvent $event
     */
    public function onProductUpdate(GenericEvent $event)
    {
        $this->onProductCreate($event);
    }
}
