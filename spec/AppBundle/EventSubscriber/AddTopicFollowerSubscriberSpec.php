<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\AppBundle\EventSubscriber;

use AppBundle\AppEvents;
use AppBundle\Context\CustomerContext;
use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use AppBundle\EventSubscriber\AddTopicFollowerSubscriber;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class AddTopicFollowerSubscriberSpec extends ObjectBehavior
{
    function let(CustomerContext $customerContext)
    {
        $this->beConstructedWith($customerContext);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AddTopicFollowerSubscriber::class);
    }

    function it_is_subscriber()
    {
        $this->shouldImplement(EventSubscriberInterface::class);
    }

    function it_subscriber_to_event()
    {
        $this::getSubscribedEvents()->shouldReturn([
            AppEvents::TOPIC_PRE_CREATE => 'onTopicCreate',
            AppEvents::POST_PRE_CREATE => 'onPostCreate',
        ]);
    }

    function it_hads_currently_logged_customer_as_follower_on_topic_create($customerContext, CustomerInterface $customer, GenericEvent $event, Topic $topic)
    {
        $event->getSubject()->willReturn($topic);
        $customerContext->getCustomer()->willReturn($customer);

        $topic->addFollower($customer)->shouldBeCalled();
        $this->onTopicCreate($event);
    }

    function it_hads_currently_logged_customer_as_follower_on_post_create($customerContext, CustomerInterface $customer, GenericEvent $event, Topic $topic, Post $post)
    {
        $event->getSubject()->willReturn($post);
        $customerContext->getCustomer()->willReturn($customer);
        $post->getTopic()->willReturn($topic);

        $topic->addFollower($customer)->shouldBeCalled();
        $this->onPostCreate($event);
    }
}
