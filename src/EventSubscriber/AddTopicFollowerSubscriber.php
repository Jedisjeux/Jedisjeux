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
     * @var CustomerContextInterface
     */
    protected $customerContext;

    /**
     * AddTopicFollowerSubscriber constructor.
     *
     * @param CustomerContextInterface $customerContext
     */
    public function __construct(CustomerContextInterface $customerContext)
    {
        $this->customerContext = $customerContext;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            AppEvents::TOPIC_PRE_CREATE => 'onTopicCreate',
            AppEvents::POST_PRE_CREATE => 'onPostCreate',
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function onTopicCreate(GenericEvent $event)
    {
        /** @var Topic $topic */
        $topic = $event->getSubject();

        $topic->addFollower($this->getCustomer());
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

        $topic->addFollower($this->getCustomer());
    }

    /**
     * @return CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customerContext->getCustomer();
    }
}
