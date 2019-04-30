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

namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface SubscriptionInterface extends ResourceInterface
{
    public function getSubject(): ?SubscribableInterface;

    public function setSubject(?SubscribableInterface $subject): void;

    public function getSubscriber(): ?SubscriberInterface;

    public function setSubscriber(?SubscriberInterface $subscriber): void;

    public function getOptions(): array;

    public function hasOption(string $option): bool;

    public function addOption(string $option): void;

    public function removeOption(string $option): void;
}