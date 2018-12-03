<?php

/*
 * This file is part of Jedisjeux projet.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\Entity\Product;
use App\Event\ProductEvents;
use App\Updater\ProductCountByTaxonUpdater;
use App\Updater\TopicCountByTaxonUpdater;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CalculateProductCountByTaxonSubscriber implements EventSubscriberInterface
{
    /**
     * @var TopicCountByTaxonUpdater
     */
    protected $updater;

    /**
     * CalculateProductCountByTaxonSubscriber constructor.
     *
     * @param ProductCountByTaxonUpdater $updater
     */
    public function __construct(ProductCountByTaxonUpdater $updater)
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

        $taxon = $product->getMainTaxon();

        if (null !== $taxon) {
            $this->updater->update($taxon);
        }

        foreach ($product->getTaxons() as $taxon) {
            $this->updater->update($taxon);
        }
    }

    /**
     * @param GenericEvent $event
     */
    public function onProductUpdate(GenericEvent $event)
    {
        $this->onProductCreate($event);
    }
}
