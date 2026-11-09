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
use App\Factory\TopicFactory;
use App\Repository\TopicRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CreateTopicForGamePlaySubscriber implements EventSubscriberInterface
{
    /**
     * CreateTopicForGamePlaySubscriber constructor.
     */
    public function __construct(
        private ObjectManager $manager,
        private TopicRepository $topicRepository,
        private TopicFactory $topicFactory
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AppEvents::POST_PRE_CREATE => 'onCreate',
        ];
    }

    public function onCreate(GenericEvent $event): void
    {
        /** @var Post $post */
        $post = $event->getSubject();
        $gamePlay = $post->getGamePlay();

        if (null === $gamePlay) {
            return;
        }

        $topic = $this->topicRepository->findOneByGamePlay($gamePlay);

        if (null == $topic) {
            $topic = $this->topicFactory->createForGamePlay($gamePlay);
            $this->manager->persist($topic);
        }

        $topic->addPost($post);
        $topic->addFollower($post->getAuthor());
    }
}
