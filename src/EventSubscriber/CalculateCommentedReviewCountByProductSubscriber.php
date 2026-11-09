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
use App\Updater\CommentedReviewCountByProductUpdater;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class CalculateCommentedReviewCountByProductSubscriber implements EventSubscriber
{
    public function __construct(protected CommentedReviewCountByProductUpdater $updater)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->updateReviewCount($args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->updateReviewCount($args);
    }

    public function updateReviewCount(LifecycleEventArgs $args): void
    {
        $product = $args->getObject();

        if (!$product instanceof Product) {
            return;
        }

        $this->updater->update($product);
    }
}
