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
use AppBundle\Entity\Topic;
use Sylius\Bundle\UserBundle\Context\CustomerContext;
use Sylius\Component\User\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class AddTopicFollowerSubscriber implements EventSubscriberInterface
{
    /**
     * @var CustomerContext
     */
    protected $customerContext;

    /**
     * AddTopicFollowerSubscriber constructor.
     *
     * @param CustomerContext $customerContext
     */
    public function __construct(CustomerContext $customerContext)
    {
        $this->customerContext = $customerContext;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            AppEvents::TOPIC_PRE_CREATE => 'onTopicCreate',
        );
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
     * @return CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customerContext->getCustomer();
    }
}
