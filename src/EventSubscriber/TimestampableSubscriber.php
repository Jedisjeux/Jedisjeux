<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\View;

namespace App\EventSubscriber;

use App\Entity\TimestampableTrait;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

final class TimestampableSubscriber implements EventSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $event): void
    {
        $this->updateTimestamps($event->getEntity());
    }

    public function preUpdate(LifecycleEventArgs $event): void
    {
        $this->updateTimestamps($event->getEntity());
    }

    private function updateTimestamps(object $entity): void
    {
        foreach ([get_class($entity)] + class_parents($entity) as $class) {
            if (in_array(TimestampableTrait::class, class_uses($class), true)) {
                $entity->updateTimestamps();

                return;
            }
        }
    }
}
