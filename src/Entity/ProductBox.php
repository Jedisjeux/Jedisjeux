<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_product_box")
 */
class ProductBox implements ResourceInterface
{
    use IdentifiableTrait, Timestampable;

    /**
     * @var ProductVariantInterface|null
     *
     * @ORM\OneToOne(targetEntity="ProductVariant", mappedBy="box")
     */
    protected $productVariant;

    /**
     * @var ProductBoxImage|null
     *
     * @ORM\OneToOne(targetEntity="ProductBoxImage", cascade={"persist"})
     */
    protected $image;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal")
     */
    protected $height;

    /**
     * @return ProductBoxImage|null
     */
    public function getImage(): ?ProductBoxImage
    {
        return $this->image;
    }

    /**
     * @param ProductBoxImage|null $image
     */
    public function setImage(?ProductBoxImage $image): void
    {
        $this->image = $image;
    }

    /**
     * @return float|null
     */
    public function getHeight(): ?float
    {
        return $this->height;
    }

    /**
     * @param float|null $height
     */
    public function setHeight(?float $height): void
    {
        $this->height = $height;
    }

    /**
     * @return ProductVariantInterface|null
     */
    public function getProductVariant(): ?ProductVariantInterface
    {
        return $this->productVariant;
    }

    /**
     * @param ProductVariantInterface|null $productVariant
     */
    public function setProductVariant(?ProductVariantInterface $productVariant): void
    {
        $this->productVariant = $productVariant;
    }
}
