<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\GamePlay;
use AppBundle\Updater\ImageCountByGamePlayUpdater;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CalculateImageCountByGamePlaySubscriber implements EventSubscriber
{
    /**
     * @var ImageCountByGamePlayUpdater
     */
    protected $updater;

    /**
     * CalculatePostCountByTopicSubscriber constructor.
     *
     * @param ImageCountByGamePlayUpdater $updater
     */
    public function __construct(ImageCountByGamePlayUpdater $updater)
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

    /**
     * {@inheritdoc}
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->updateImageCount($args);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->updateImageCount($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function updateImageCount(LifecycleEventArgs $args)
    {
        $gamePlay = $args->getObject();

        if (!$gamePlay instanceof GamePlay) {
            return;
        }

        $this->updater->update($gamePlay);
    }
}
