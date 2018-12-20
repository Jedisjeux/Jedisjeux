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
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="jdj_product_video")
 */
class ProductVideo implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(groups={"sylius"})
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(groups={"sylius"})
     */
    private $path;

    /**
     * @var ProductInterface|null
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface", inversedBy="videos")
     */
    private $product;

    /**
     * @var ProductVideoImage|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\ProductVideoImage", cascade={"persist", "remove"})
     */
    private $image;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     */
    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return ProductVideoImage|null
     */
    public function getImage(): ?ProductVideoImage
    {
        return $this->image;
    }

    /**
     * @param ProductVideoImage|null $image
     */
    public function setImage(?ProductVideoImage $image): void
    {
        $this->image = $image;
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
}
