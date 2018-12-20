<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="jdj_product_image")
 *
 * @JMS\ExclusionPolicy("all")
 */
class ProductImage extends AbstractImage
{
    /**
     * The associated product.
     *
     * @var ProductInterface|null
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface", inversedBy="images")
     */
    protected $product;

    /**
     * The associated product variant.
     *
     * @var ProductVariantInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductVariantInterface", inversedBy="images")
     */
    protected $variant;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", name="is_main")
     */
    protected $main;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", name="is_material")
     */
    protected $material;

    /**
     * ProductVariantImage constructor.
     */
    public function __construct()
    {
        $this->main = false;
        $this->material = false;
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
     * @return ProductVariantInterface|null
     */
    public function getVariant(): ?ProductVariantInterface
    {
        return $this->variant;
    }

    /**
     * @param ProductVariantInterface|null $variant
     */
    public function setVariant(?ProductVariantInterface $variant): void
    {
        $this->variant = $variant;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function isMain(): bool
    {
        return $this->main;
    }

    /**
     * @param bool $main
     */
    public function setMain(bool $main): void
    {
        $this->main = $main;
    }

    /**
     * @return bool
     */
    public function isMaterial(): bool
    {
        return $this->material;
    }

    /**
     * @param bool $material
     */
    public function setMaterial($material): void
    {
        $this->material = $material;
    }
}
