<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class Subscription implements SubscriptionInterface
{
    use IdentifiableTrait;

    public static $defaultOptions = [];

    /**
     * @var SubscribableInterface|null
     */
    protected $subject;

    /**
     * @var SubscriberInterface|null
     */
    protected $subscriber;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $options = [];

    public function __construct()
    {
        $this->options = static::$defaultOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject(): ?SubscribableInterface
    {
        return $this->subject;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubject(?SubscribableInterface $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscriber(): ?SubscriberInterface
    {
        return $this->subscriber;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubscriber(?SubscriberInterface $subscriber): void
    {
        $this->subscriber = $subscriber;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption(string $option): bool
    {
        return in_array($option, $this->getOptions());
    }

    /**
     * {@inheritdoc}
     */
    public function addOption(string $option): void
    {
        if (!in_array($option, $this->options, true)) {
            $this->options[] = $option;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeOption(string $option): void
    {
        if (false !== $key = array_search($option, $this->options)) {
            unset($this->options[$key]);
            $this->options = array_values($this->options);
        }
    }
}
