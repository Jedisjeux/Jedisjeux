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

use Sylius\Component\Product\Model\ProductVariantInterface as BaseProductVariantInterface;

interface ProductVariantInterface extends BaseProductVariantInterface
{
    /**
     * @return ProductBox|null
     */
    public function getBox(): ?ProductBox;

    /**
     * @param ProductBox|null $box
     */
    public function setBox(?ProductBox $box): void;
}
