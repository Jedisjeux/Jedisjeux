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

use AppBundle\AppEvents;
use AppBundle\Entity\Post;
use AppBundle\Updater\PostCountByTopicUpdater;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CalculatePostCountByTopicSubscriber implements EventSubscriberInterface
{
    /**
     * @var PostCountByTopicUpdater
     */
    protected $updater;

    /**
     * CalculatePostCountByTopicSubscriber constructor.
     *
     * @param PostCountByTopicUpdater $updater
     */
    public function __construct(PostCountByTopicUpdater $updater)
    {
        $this->updater = $updater;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            AppEvents::POST_PRE_CREATE => 'onPostCreate',
        );
    }

    /**
     * @param GenericEvent $event
     */
    public function onPostCreate(GenericEvent $event)
    {
        /** @var Post $post */
        $post = $event->getSubject();

        $this->updater->update($post->getTopic());
    }
}
