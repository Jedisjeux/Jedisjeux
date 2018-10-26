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
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CreateTopicForGamePlaySubscriber implements EventSubscriberInterface
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var TopicRepository
     */
    protected $topicRepository;

    /**
     * @var TopicFactory
     */
    protected $topicFactory;

    /**
     * CreateTopicForGamePlaySubscriber constructor.
     *
     * @param ObjectManager   $manager
     * @param TopicRepository $topicRepository
     * @param TopicFactory    $topicFactory
     */
    public function __construct(ObjectManager $manager, TopicRepository $topicRepository, TopicFactory $topicFactory)
    {
        $this->manager = $manager;
        $this->topicRepository = $topicRepository;
        $this->topicFactory = $topicFactory;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            AppEvents::POST_PRE_CREATE => 'onCreate',
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function onCreate(GenericEvent $event)
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
