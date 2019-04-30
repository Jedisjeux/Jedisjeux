<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\App\Factory;

use App\Entity\SubscribableInterface;
use App\Entity\SubscriberInterface;
use App\Entity\SubscriptionInterface;
use App\Factory\SubscriptionFactory;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Factory\FactoryInterface;

class SubscriptionFactorySpec extends ObjectBehavior
{
    function let(FactoryInterface $factory)
    {
        $this->beConstructedWith($factory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(SubscriptionFactory::class);
    }

    function it_implements_factory_interface(): void
    {
        $this->shouldImplement(FactoryInterface::class);
    }

    function it_creates_subscriptions(FactoryInterface $factory, SubscriptionInterface $subscription): void
    {
        $factory->createNew()->willReturn($subscription);

        $this->createNew()->shouldReturn($subscription);
    }

    function it_creates_subscriptions_with_subject(
        FactoryInterface $factory,
        SubscriptionInterface $subscription,
        SubscribableInterface $subject
    ): void {
        $factory->createNew()->willReturn($subscription);
        $subscription->setSubject($subject)->shouldBeCalled();

        $this->createForSubject($subject)->shouldReturn($subscription);
    }

    function it_creates_subscriptions_with_subject_and_subscriber(
        FactoryInterface $factory,
        SubscriptionInterface $subscription,
        SubscribableInterface $subject,
        SubscriberInterface $subscriber
    ): void {
        $factory->createNew()->willReturn($subscription);
        $subscription->setSubject($subject)->shouldBeCalled();
        $subscription->setSubscriber($subscriber)->shouldBeCalled();

        $this->createForSubjectWithSubscriber($subject, $subscriber)->shouldReturn($subscription);
    }
}
