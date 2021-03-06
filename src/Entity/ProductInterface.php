<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Sylius\Component\Product\Model\ProductInterface as BaseProductInterface;
use Sylius\Component\Review\Model\ReviewableInterface;

interface ProductInterface extends BaseProductInterface, ReviewableInterface
{
    /**
     * @return ProductVariantInterface|ProductVariant|null
     */
    public function getFirstVariant(): ?ProductVariantInterface;

    /**
     * @param ProductVariantInterface $variant
     */
    public function setFirstVariant(ProductVariantInterface $variant): void;

    public function getReviewCount(): int;

    public function setReviewCount(int $reviewCount): void;
}
