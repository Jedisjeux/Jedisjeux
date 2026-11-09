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
use App\Entity\Topic;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class AddTopicFollowerSubscriber implements EventSubscriberInterface
{
    /**
     * AddTopicFollowerSubscriber constructor.
     */
    public function __construct(private CustomerContextInterface $customerContext)
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AppEvents::TOPIC_PRE_CREATE => 'onTopicCreate',
            AppEvents::POST_PRE_CREATE => 'onPostCreate',
        ];
    }

    public function onTopicCreate(GenericEvent $event): void
    {
        /** @var Topic $topic */
        $topic = $event->getSubject();

        $topic->addFollower($this->getCustomer());
    }

    public function onPostCreate(GenericEvent $event): void
    {
        /** @var Post $post */
        $post = $event->getSubject();

        if (null === $topic = $post->getTopic()) {
            return;
        }

        $topic->addFollower($this->getCustomer());
    }

    /**
     * @return CustomerInterface
     */
    public function getCustomer(): ?CustomerInterface
    {
        return $this->customerContext->getCustomer();
    }
}
