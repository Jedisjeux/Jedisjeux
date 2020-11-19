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

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_product_box")
 */
class ProductBox implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable,
        ToggleableTrait;

    const RATIO = 0.645;

    const STATUS_NEW = 'new';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @var ProductInterface|null
     *
     * @ORM\ManyToOne(targetEntity="Product")
     */
    private $product;

    /**
     * @var ProductVariantInterface|null
     *
     * @ORM\ManyToOne(targetEntity="ProductVariant", inversedBy="boxes")
     */
    private $productVariant;

    /**
     * @var ProductBoxImage|null
     *
     * @ORM\OneToOne(targetEntity="ProductBoxImage", cascade={"persist"})
     *
     * @Assert\Valid
     */
    private $image;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $height;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank
     */
    private $realHeight;

    /**
     * @var CustomerInterface|null
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Customer\Model\CustomerInterface")
     */
    private $author;

    public function __construct()
    {
        $this->status = static::STATUS_NEW;
        $this->disable();
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getImage(): ?ProductBoxImage
    {
        return $this->image;
    }

    public function setImage(?ProductBoxImage $image): void
    {
        $this->image = $image;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): void
    {
        $this->height = $height;
    }

    public function getRealHeight(): ?int
    {
        return $this->realHeight;
    }

    public function setRealHeight(?int $realHeight): void
    {
        $this->realHeight = $realHeight;

        if (null === $realHeight) {
            $this->setHeight(null);
        } else {
            $height = round($realHeight * static::RATIO);
            $this->setHeight($height);
        }
    }

    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;

        if (null === $product) {
            $this->setProductVariant(null);

            return;
        }

        $this->setProductVariant($product->getFirstVariant());
    }

    public function getProductVariant(): ?ProductVariantInterface
    {
        return $this->productVariant;
    }

    public function setProductVariant(?ProductVariantInterface $productVariant): void
    {
        $this->productVariant = $productVariant;
    }

    public function getAuthor(): ?CustomerInterface
    {
        return $this->author;
    }

    public function setAuthor(?CustomerInterface $author): void
    {
        $this->author = $author;
    }
}
