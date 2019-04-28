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
use Webmozart\Assert\Assert;

class ProductSubscription extends Subscription implements ResourceInterface
{
    public const OPTION_FOLLOW_ARTICLES = 'follow_articles';
    public const OPTION_FOLLOW_REVIEWS = 'follow_reviews';
    public const OPTION_FOLLOW_GAME_PLAYS = 'follow_game_plays';
    public const OPTION_FOLLOW_VIDEOS = 'follow_videos';
    public const OPTION_FOLLOW_FILES = 'follow_files';

    protected $options = [
        self::OPTION_FOLLOW_ARTICLES,
        self::OPTION_FOLLOW_REVIEWS,
        self::OPTION_FOLLOW_GAME_PLAYS,
        self::OPTION_FOLLOW_VIDEOS,
        self::OPTION_FOLLOW_FILES,
    ];

    /**
     * @var ProductInterface|null
     */
    protected $subject;

    /**
     * @return Subscribable|ProductInterface|null
     */
    public function getSubject(): ?Subscribable
    {
        return $this->subject;
    }

    /**
     * @param Subscribable|ProductInterface|null $product
     */
    public function setSubject(?Subscribable $product): void
    {
        Assert::nullOrIsInstanceOf($product, ProductInterface::class);

        $this->subject = $product;
    }
}
