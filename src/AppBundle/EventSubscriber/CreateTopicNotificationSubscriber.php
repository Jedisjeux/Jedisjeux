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
use AppBundle\Factory\NotificationFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\UserBundle\Context\CustomerContext;
use Sylius\Component\User\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CreateTopicNotificationSubscriber implements EventSubscriberInterface
{
    /**
     * @var CustomerContext
     */
    protected $customerContext;

    /**
     * @var NotificationFactory
     */
    protected $factory;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * CreateTopicNotificationSubscriber constructor.
     *
     * @param CustomerContext $customerContext
     * @param NotificationFactory $factory
     * @param ObjectManager $manager
     */
    public function __construct(CustomerContext $customerContext, NotificationFactory $factory, ObjectManager $manager)
    {
        $this->customerContext = $customerContext;
        $this->factory = $factory;
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            AppEvents::POST_POST_CREATE => 'onPostCreate',
        );
    }

    /**
     * @param GenericEvent $event
     */
    public function onPostCreate(GenericEvent $event)
    {
        /** @var Post $post */
        $post = $event->getSubject();

        foreach ($post->getTopic()->getFollowers() as $follower) {
            /**
             * Don't notify the current customer
             */
            if ($follower === $this->getCustomer()) {
                continue;
            }

            $notification = $this->factory->createForPost($post, $follower);
            $this->manager->persist($notification);
        }

        $this->manager->flush();
    }

    /**
     * @return CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customerContext->getCustomer();
    }
}
