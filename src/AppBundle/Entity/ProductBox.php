<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

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
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param ProductBoxImage|null $image
     *
     * @return ProductBox
     */
    public function setImage(ProductBoxImage $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param float $height
     *
     * @return ProductBox
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return ProductVariantInterface|null
     */
    public function getProductVariant()
    {
        return $this->productVariant;
    }

    /**
     * @param ProductVariantInterface|null $productVariant
     * @return ProductBox
     */
    public function setProductVariant(ProductVariantInterface $productVariant = null)
    {
        $this->productVariant = $productVariant;

        return $this;
    }
}
