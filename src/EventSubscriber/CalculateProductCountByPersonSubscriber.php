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

use App\Entity\Product;
use App\Event\ProductEvents;
use App\Updater\ProductCountByPersonUpdater;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CalculateProductCountByPersonSubscriber implements EventSubscriberInterface
{
    /**
     * @var ProductCountByPersonUpdater
     */
    protected $updater;

    /**
     * CalculateProductCountByTaxonSubscriber constructor.
     */
    public function __construct(ProductCountByPersonUpdater $updater)
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

    public function onProductCreate(GenericEvent $event)
    {
        /** @var Product $product */
        $product = $event->getSubject();

        foreach ($product->getDesigners() as $designer) {
            $this->updater->update($designer);
        }

        foreach ($product->getArtists() as $artist) {
            $this->updater->update($artist);
        }

        foreach ($product->getPublishers() as $publisher) {
            $this->updater->update($publisher);
        }
    }

    public function onProductUpdate(GenericEvent $event)
    {
        $this->onProductCreate($event);
    }
}
