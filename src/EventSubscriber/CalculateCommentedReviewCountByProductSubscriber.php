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
use App\Updater\CommentedReviewCountByProductUpdater;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class CalculateCommentedReviewCountByProductSubscriber implements EventSubscriber
{
    /**
     * @var CommentedReviewCountByProductUpdater
     */
    protected $updater;

    public function __construct(CommentedReviewCountByProductUpdater $updater)
    {
        $this->updater = $updater;
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

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->updateReviewCount($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->updateReviewCount($args);
    }

    public function updateReviewCount(LifecycleEventArgs $args)
    {
        $product = $args->getObject();

        if (!$product instanceof Product) {
            return;
        }

        $this->updater->update($product);
    }
}
