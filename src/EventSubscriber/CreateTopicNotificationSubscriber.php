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
use App\Entity\Notification;
use App\Entity\Post;
use App\Factory\NotificationFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Translation\Translator;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CreateTopicNotificationSubscriber implements EventSubscriberInterface
{
    /**
     * @var CustomerContextInterface
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
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * CreateTopicNotificationSubscriber constructor.
     *
     * @param CustomerContextInterface $customerContext
     * @param NotificationFactory $factory
     * @param ObjectManager $manager
     * @param EntityRepository $repository
     * @param Translator $translator
     */
    public function __construct(CustomerContextInterface $customerContext, NotificationFactory $factory, ObjectManager $manager, EntityRepository $repository, Translator $translator)
    {
        $this->customerContext = $customerContext;
        $this->factory = $factory;
        $this->manager = $manager;
        $this->repository = $repository;
        $this->translator = $translator;
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

        if (null === $topic = $post->getTopic()) {
            return;
        }

        foreach ($topic->getFollowers() as $follower) {
            /**
             * Don't notify the current customer
             */
            if ($follower === $this->getCustomer()) {
                continue;
            }

            /** @var Notification $notification */
            $notification = $this->repository->findOneBy([
                'recipient' => $follower,
                'topic' => $topic,
                'read' => 0,
            ]);

            if (null === $notification) {
                $notification = $this->factory->createForPost($post, $follower);
                $this->manager->persist($notification);
            }

            $notification->addAuthor($post->getAuthor());

            if (count($notification->getAuthors()) > 1) {
                $notification->setMessage($this->translator->trans('text.notification.topic_replies', [
                    '%USERNAMES%' => implode(', ', $notification->getAuthors()->toArray()),
                    '%TOPIC_NAME%' => $post->getTopic()->getTitle(),
                ]));
            } else {
                $notification->setMessage($this->translator->trans('text.notification.topic_reply', [
                    '%USERNAME%' => $notification->getAuthors()->first(),
                    '%TOPIC_NAME%' => $post->getTopic()->getTitle(),
                ]));
            }
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
