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
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="jdj_product_subscription")
 */
class ProductSubscription extends Subscription implements SubscriptionInterface
{
    public const OPTION_FOLLOW_ARTICLES = 'follow_articles';
    public const OPTION_FOLLOW_REVIEWS = 'follow_reviews';
    public const OPTION_FOLLOW_GAME_PLAYS = 'follow_game_plays';
    public const OPTION_FOLLOW_VIDEOS = 'follow_videos';
    public const OPTION_FOLLOW_FILES = 'follow_files';

    public static $defaultOptions = [
        self::OPTION_FOLLOW_ARTICLES,
        self::OPTION_FOLLOW_REVIEWS,
        self::OPTION_FOLLOW_GAME_PLAYS,
        self::OPTION_FOLLOW_VIDEOS,
        self::OPTION_FOLLOW_FILES,
    ];

    /**
     * @var ProductInterface|null
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface")
     */
    protected $subject;

    /**
     * @var CustomerInterface|null
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Customer\Model\CustomerInterface", inversedBy="productSubscriptions")
     */
    protected $subscriber;

    /**
     * @return SubscribableInterface|ProductInterface|null
     */
    public function getSubject(): ?SubscribableInterface
    {
        return $this->subject;
    }

    /**
     * @param SubscribableInterface|ProductInterface|null $product
     */
    public function setSubject(?SubscribableInterface $product): void
    {
        Assert::nullOrIsInstanceOf($product, ProductInterface::class);

        $this->subject = $product;
    }
}
