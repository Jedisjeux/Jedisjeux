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
abstract class Subscription
{
    use IdentifiableTrait;

    public static $defaultOptions = [];

    /**
     * @var Subscribable|null
     */
    protected $subject;

    /**
     * @var CustomerInterface|null
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
     * @return Subscribable|null
     */
    public function getSubject(): ?Subscribable
    {
        return $this->subject;
    }

    /**
     * @param Subscribable|null $subject
     */
    public function setSubject(?Subscribable $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return CustomerInterface|null
     */
    public function getSubscriber(): ?CustomerInterface
    {
        return $this->subscriber;
    }

    /**
     * @param CustomerInterface|null $subscriber
     */
    public function setSubscriber(?CustomerInterface $subscriber): void
    {
        $this->subscriber = $subscriber;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param string $option
     * @return bool
     */
    public function hasOption(string $option): bool
    {
        return in_array($option, $this->getOptions());
    }

    /**
     * @param string $option
     */
    public function addOption(string $option): void
    {
        if (!in_array($option, $this->options, true)) {
            $this->options[] = $option;
        }
    }

    /**
     * @param string $option
     */
    public function removeOption(string $option): void
    {
        if (false !== $key = array_search($option, $this->options)) {
            unset($this->options[$key]);
            $this->options = array_values($this->options);
        }
    }
}
