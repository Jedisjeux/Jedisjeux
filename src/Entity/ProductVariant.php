<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 07/03/2016
 * Time: 17:03.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Sylius\Component\Product\Model\ProductVariant as BaseProductVariant;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_variant")
 *
 * @JMS\ExclusionPolicy("all")
 */
class ProductVariant extends BaseProductVariant
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
     * @var ProductBox|null
     *
     * @ORM\OneToOne(targetEntity="ProductBox", inversedBy="productVariant")
     */
    protected $box;

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
        $this->code = uniqid('variant_');
    }

    /**
     * @return ProductVariantImage|null
     */
    public function getMainImage(): ?ProductVariantImage
    {
        foreach ($this->images as $image) {
            if ($image->isMain()) {
                return $image;
            }
        }

        return null;
    }

    /**
     * @return ProductVariantImage|null
     */
    public function getMaterialImage(): ?ProductVariantImage
    {
        foreach ($this->images as $image) {
            if ($image->isMaterial()) {
                return $image;
            }
        }

        return null;
    }

    /**
     * @param ProductVariantImage $image
     *
     * @return bool
     */
    public function hasImage(ProductVariantImage $image): bool
    {
        return $this->images->contains($image);
    }

    /**
     * @param ProductVariantImage $image
     */
    public function addImage(ProductVariantImage $image): void
    {
        if (!$this->hasImage($image)) {
            $image->setVariant($this);
            $this->images->add($image);
        }
    }

    /**
     * @param ProductVariantImage $image
     */
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

    /**
     * @return \DateTime|null
     */
    public function getReleasedAt(): ?\DateTime
    {
        return $this->releasedAt;
    }

    /**
     * @param \DateTime|null $releasedAt
     */
    public function setReleasedAt(?\DateTime $releasedAt): void
    {
        $this->releasedAt = $releasedAt;
    }

    /**
     * @return string|null
     */
    public function getReleasedAtPrecision(): ?string
    {
        return $this->releasedAtPrecision;
    }

    /**
     * @param string|null $releasedAtPrecision
     */
    public function setReleasedAtPrecision(?string $releasedAtPrecision): void
    {
        $this->releasedAtPrecision = $releasedAtPrecision;
    }

    /**
     * @return string|null
     */
    public function getOldHref(): ?string
    {
        return $this->oldHref;
    }

    /**
     * @param string|null $oldHref
     */
    public function setOldHref(?string $oldHref): void
    {
        $this->oldHref = $oldHref;
    }

    /**
     * @return ProductBox|null
     */
    public function getBox(): ?ProductBox
    {
        return $this->box;
    }

    /**
     * @param ProductBox|null $box
     */
    public function setBox(?ProductBox $box): void
    {
        $this->box = $box;
    }

    /**
     * @return Collection|Person[]
     */
    public function getDesigners(): Collection
    {
        return $this->designers;
    }

    /**
     * @param Person $designer
     *
     * @return bool
     */
    public function hasDesigner(Person $designer): bool
    {
        return $this->designers->contains($designer);
    }

    /**
     * @param Person $designer
     */
    public function addDesigner(Person $designer): void
    {
        if (!$this->hasDesigner($designer)) {
            $this->designers->add($designer);
        }
    }

    /**
     * @param Person $designer
     */
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

    /**
     * @param Person $artist
     *
     * @return bool
     */
    public function hasArtist(Person $artist): bool
    {
        return $this->artists->contains($artist);
    }

    /**
     * @param Person $artist
     */
    public function addArtist(Person $artist): void
    {
        if (!$this->hasArtist($artist)) {
            $this->artists->add($artist);
        }
    }

    /**
     * @param Person $artist
     */
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

    /**
     * @param Person $publisher
     *
     * @return bool
     */
    public function hasPublisher(Person $publisher): bool
    {
        return $this->publishers->contains($publisher);
    }

    /**
     * @param Person $publisher
     */
    public function addPublisher(Person $publisher): void
    {
        if (!$this->publishers->contains($publisher)) {
            $this->publishers->add($publisher);
        }
    }

    /**
     * @param Person $publisher
     */
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
