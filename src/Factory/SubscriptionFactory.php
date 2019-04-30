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

namespace App\Factory;

use App\Entity\SubscribableInterface;
use App\Entity\SubscriberInterface;
use App\Entity\SubscriptionInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class SubscriptionFactory implements FactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createNew(): SubscriptionInterface
    {
        /** @var SubscriptionInterface $subscription */
        $subscription = $this->factory->createNew();

        return $subscription;
    }

    public function createForSubject(SubscribableInterface $subject): SubscriptionInterface
    {
        $subscription = $this->createNew();
        $subscription->setSubject($subject);

        return $subscription;
    }

    public function createForSubjectWithSubscriber(SubscribableInterface $subject, SubscriberInterface $subscriber): SubscriptionInterface
    {
        $subscription = $this->createForSubject($subject);
        $subscription->setSubscriber($subscriber);

        return $subscription;
    }
}
