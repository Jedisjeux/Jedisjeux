<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\AppEvents;
use App\Entity\Post;
use App\Updater\PostCountByTopicUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class CalculatePostCountByTopicSubscriber implements EventSubscriberInterface
{
    /** @var PostCountByTopicUpdater */
    private $updater;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(PostCountByTopicUpdater $updater, EntityManagerInterface $entityManager)
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
            AppEvents::POST_POST_CREATE => 'onPostCreate',
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function onPostCreate(GenericEvent $event)
    {
        /** @var Post $post */
        $post = $event->getSubject();

        if (null === $topic = $post->getTopic()) {
            return;
        }

        $this->updater->update($post->getTopic());
        $this->entityManager->flush();
    }
}
