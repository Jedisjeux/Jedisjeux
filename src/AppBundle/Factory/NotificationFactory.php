<?php

/*
 * This file is part of VPS.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Factory;

use AppBundle\Entity\Notification;
use AppBundle\Entity\Post;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\User\Model\CustomerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Translation\Translator;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class NotificationFactory extends Factory
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @param CustomerInterface $customer
     *
     * @return Notification
     */
    public function createForCustomer(CustomerInterface $customer)
    {
        /** @var Notification $notification */
        $notification = $this->createNew();

        $notification->setRecipient($customer);

        return $notification;
    }

    /**
     * @param Post $post
     * @param CustomerInterface $customer
     *
     * @return Notification
     */
    public function createForPost(Post $post, CustomerInterface $customer)
    {
        /** @var Notification $notification */
        $notification = $this->createForCustomer($customer);

        /** TODO use translation */
        $notification
            ->setTopic($post->getTopic())
            ->setTarget($this->router->generate('app_post_index_by_topic', ['topicId' => $post->getTopic()->getId()]))
            ->setMessage($this->translator->trans('text.notification.topic_reply', [
                '%USERNAME%' => $post->getCreatedBy()->getCustomer(),
                '%TOPIC_NAME%' => $post->getTopic()->getTitle(),
            ]));

        return $notification;
    }

    /**
     * @param Router $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }

    /**
     * @param Translator $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }
}
