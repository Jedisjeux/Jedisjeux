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

use Sylius\Component\Resource\Model\ResourceInterface;

class ProductSubscription implements ResourceInterface
{
    use IdentifiableTrait;

    /**
     * @var CustomerInterface|null
     */
    private $subscriber;

    /**
     * @var ProductInterface|null
     */
    private $product;

    /**
     * @var bool
     */
    private $followArticles = true;

    /**
     * @var bool
     */
    private $followReviews = true;

    /**
     * @var bool
     */
    private $followGamePlays = true;

    /**
     * @var bool
     */
    private $followVideos = true;

    /**
     * @var bool
     */
    private $followFiles = true;

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
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    /**
     * @param ProductInterface|null $product
     */
    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }

    /**
     * @return bool
     */
    public function isFollowArticles(): bool
    {
        return $this->followArticles;
    }

    /**
     * @param bool $followArticles
     */
    public function setFollowArticles(bool $followArticles): void
    {
        $this->followArticles = $followArticles;
    }

    /**
     * @return bool
     */
    public function isFollowReviews(): bool
    {
        return $this->followReviews;
    }

    /**
     * @param bool $followReviews
     */
    public function setFollowReviews(bool $followReviews): void
    {
        $this->followReviews = $followReviews;
    }

    /**
     * @return bool
     */
    public function isFollowGamePlays(): bool
    {
        return $this->followGamePlays;
    }

    /**
     * @param bool $followGamePlays
     */
    public function setFollowGamePlays(bool $followGamePlays): void
    {
        $this->followGamePlays = $followGamePlays;
    }

    /**
     * @return bool
     */
    public function isFollowVideos(): bool
    {
        return $this->followVideos;
    }

    /**
     * @param bool $followVideos
     */
    public function setFollowVideos(bool $followVideos): void
    {
        $this->followVideos = $followVideos;
    }

    /**
     * @return bool
     */
    public function isFollowFiles(): bool
    {
        return $this->followFiles;
    }

    /**
     * @param bool $followFiles
     */
    public function setFollowFiles(bool $followFiles): void
    {
        $this->followFiles = $followFiles;
    }
}
