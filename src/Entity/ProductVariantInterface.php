<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Product\Model\ProductVariantInterface as BaseProductVariantInterface;

interface ProductVariantInterface extends BaseProductVariantInterface
{
    /**
     * @return ProductBox[]|Collection
     */
    public function getBoxes(): Collection;

    public function hasBox(ProductBox $box): bool;

    /**
     * {@inheritdoc}
     */
    public function addBox(ProductBox $box): void;

    /**
     * {@inheritdoc}
     */
    public function removeBox(ProductBox $box): void;

    public function getEnabledBox(): ?ProductBox;
}
