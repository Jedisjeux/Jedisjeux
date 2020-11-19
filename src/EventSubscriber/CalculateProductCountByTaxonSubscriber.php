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

    public function onProductUpdate(GenericEvent $event)
    {
        $this->onProductCreate($event);
    }
}
