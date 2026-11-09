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
    public function __construct(private PostCountByTopicUpdater $updater, private EntityManagerInterface $entityManager)
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AppEvents::POST_POST_CREATE => 'onPostCreate',
        ];
    }

    public function onPostCreate(GenericEvent $event): void
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
