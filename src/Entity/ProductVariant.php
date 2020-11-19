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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Sylius\Component\Product\Model\ProductVariant as BaseProductVariant;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_variant")
 *
 * @JMS\ExclusionPolicy("all")
 */
class ProductVariant extends BaseProductVariant implements ProductVariantInterface
{
    const RELEASED_AT_PRECISION_ON_DAY = 'on-day';
    const RELEASED_AT_PRECISION_ON_MONTH = 'on-month';
    const RELEASED_AT_PRECISION_ON_YEAR = 'on-year';

    /**
     * @var ArrayCollection|ProductVariantImage[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ProductVariantImage", mappedBy="variant", cascade={"persist", "merge", "remove"})
     *
     * @JMS\Groups({"Detailed"})
     */
    protected $images;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @JMS\Groups({"Detailed"})
     */
    protected $releasedAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @JMS\Groups({"Detailed"})
     */
    protected $releasedAtPrecision;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $oldHref;

    /**
     * @var ProductBox[]|Collection
     *
     * @ORM\OneToMany(targetEntity="ProductBox", mappedBy="productVariant")
     */
    protected $boxes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Person", inversedBy="designerProducts", cascade={"persist", "merge"})
     * @ORM\JoinTable(
     *      name="jdj_designer_product_variant",
     *      joinColumns={@ORM\JoinColumn(name="productvariant_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="person_id", referencedColumnName="id")}
     * )
     */
    protected $designers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Person", inversedBy="artistProducts", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="jdj_artist_product_variant",
     *      joinColumns={@ORM\JoinColumn(name="productvariant_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="person_id", referencedColumnName="id")}
     * )
     */
    protected $artists;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Person", inversedBy="publisherProducts", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="jdj_publisher_product_variant",
     *      joinColumns={@ORM\JoinColumn(name="productvariant_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="person_id", referencedColumnName="id")}
     * )
     */
    protected $publishers;

    /**
     * ProductVariant constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->images = new ArrayCollection();
        $this->designers = new ArrayCollection();
        $this->artists = new ArrayCollection();
        $this->publishers = new ArrayCollection();
        $this->boxes = new ArrayCollection();
        $this->code = uniqid('variant_');
    }

    public function getMainImage(): ?ProductVariantImage
    {
        foreach ($this->images as $image) {
            if ($image->isMain()) {
                return $image;
            }
        }

        return null;
    }

    public function getMaterialImage(): ?ProductVariantImage
    {
        foreach ($this->images as $image) {
            if ($image->isMaterial()) {
                return $image;
            }
        }

        return null;
    }

    public function hasImage(ProductVariantImage $image): bool
    {
        return $this->images->contains($image);
    }

    public function addImage(ProductVariantImage $image): void
    {
        if (!$this->hasImage($image)) {
            $image->setVariant($this);
            $this->images->add($image);
        }
    }

    public function removeImage(ProductVariantImage $image): void
    {
        $this->images->removeElement($image);
    }

    /**
     * @return Collection|ProductVariantImage[]
     */
    public function getImages(): ?Collection
    {
        return $this->images;
    }

    public function getReleasedAt(): ?\DateTime
    {
        return $this->releasedAt;
    }

    public function setReleasedAt(?\DateTime $releasedAt): void
    {
        $this->releasedAt = $releasedAt;
    }

    public function getReleasedAtPrecision(): ?string
    {
        return $this->releasedAtPrecision;
    }

    public function setReleasedAtPrecision(?string $releasedAtPrecision): void
    {
        $this->releasedAtPrecision = $releasedAtPrecision;
    }

    public function getOldHref(): ?string
    {
        return $this->oldHref;
    }

    public function setOldHref(?string $oldHref): void
    {
        $this->oldHref = $oldHref;
    }

    /**
     * {@inheritdoc}
     */
    public function getBoxes(): Collection
    {
        return $this->boxes;
    }

    /**
     * {@inheritdoc}
     */
    public function hasBox(ProductBox $box): bool
    {
        return $this->boxes->contains($box);
    }

    /**
     * {@inheritdoc}
     */
    public function addBox(ProductBox $box): void
    {
        if (!$this->hasBox($box)) {
            $this->boxes->add($box);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeBox(ProductBox $box): void
    {
        $this->boxes->removeElement($box);
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledBox(): ?ProductBox
    {
        $boxes = $this->boxes->filter(function (ProductBox $box) {
            return $box->isEnabled();
        });

        return count($boxes) ? $boxes->first() : null;
    }

    /**
     * @return Collection|Person[]
     */
    public function getDesigners(): Collection
    {
        return $this->designers;
    }

    public function hasDesigner(Person $designer): bool
    {
        return $this->designers->contains($designer);
    }

    public function addDesigner(Person $designer): void
    {
        if (!$this->hasDesigner($designer)) {
            $this->designers->add($designer);
        }
    }

    public function removeDesigner(Person $designer): void
    {
        $this->designers->removeElement($designer);
    }

    /**
     * @return Collection|Person[]
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function hasArtist(Person $artist): bool
    {
        return $this->artists->contains($artist);
    }

    public function addArtist(Person $artist): void
    {
        if (!$this->hasArtist($artist)) {
            $this->artists->add($artist);
        }
    }

    public function removeArtist(Person $artist): void
    {
        $this->artists->removeElement($artist);
    }

    /**
     * @return Collection|Person[]
     */
    public function getPublishers(): Collection
    {
        return $this->publishers;
    }

    public function hasPublisher(Person $publisher): bool
    {
        return $this->publishers->contains($publisher);
    }

    public function addPublisher(Person $publisher): void
    {
        if (!$this->publishers->contains($publisher)) {
            $this->publishers->add($publisher);
        }
    }

    public function removePublisher(Person $publisher): void
    {
        $this->publishers->removeElement($publisher);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        $name = $this->getTranslation()->getName();

        return !empty($name) ? $name : '';
    }
}
