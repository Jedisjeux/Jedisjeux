<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 07/03/2016
 * Time: 17:03
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Product\Model\Variant;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_variant")
 */
class ProductVariant extends Variant
{
    const RELEASED_AT_PRECISION_ON_DAY = 'on-day';
    const RELEASED_AT_PRECISION_ON_MONTH = 'on-month';
    const RELEASED_AT_PRECISION_ON_YEAR = 'on-year';

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, separator="-", unique=true)
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @var ArrayCollection|ProductVariantImage[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductVariantImage", mappedBy="variant", cascade={"persist", "merge"})
     */
    protected $images;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    protected $releasedAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $releasedAtPrecision;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Person", inversedBy="designerProducts", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="jdj_designer_product_variant")
     */
    protected $designers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Person", inversedBy="artistProducts", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="jdj_artist_product_variant")
     */
    protected $artists;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Person", inversedBy="publisherProducts", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="jdj_publisher_product_variant")
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
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return ProductVariantImage
     */
    public function getMainImage()
    {
        foreach ($this->images as $image) {
            if ($image->isMain()) {
                return $image;
            }
        }

        return null;
    }

    /**
     * @return ProductVariantImage
     */
    public function getMaterialImage()
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
     * @return $this
     */
    public function removeImage(ProductVariantImage $image)
    {
        $this->images->remove($image);

        return $this;
    }

    /**
     * @param ProductVariantImage $image
     *
     * @return $this
     */
    public function addImage(ProductVariantImage $image)
    {
        if (!$this->images->contains($image)) {
            $image->setVariant($this);
            $this->images->add($image);
        }

        return $this;
    }

    /**
     * @return ArrayCollection|ProductVariantImage[]
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @return \DateTime
     */
    public function getReleasedAt()
    {
        return $this->releasedAt;
    }

    /**
     * @param \DateTime $releasedAt
     *
     * @return $this
     */
    public function setReleasedAt($releasedAt)
    {
        $this->releasedAt = $releasedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getReleasedAtPrecision()
    {
        return $this->releasedAtPrecision;
    }

    /**
     * @param string $releasedAtPrecision
     *
     * @return $this
     */
    public function setReleasedAtPrecision($releasedAtPrecision)
    {
        $this->releasedAtPrecision = $releasedAtPrecision;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDesigners()
    {
        return $this->designers;
    }

    /**
     * @param Person $designer
     *
     * @return $this
     */
    public function addDesigner($designer)
    {
        if (!$this->designers->contains($designer)) {
            $this->designers->add($designer);
        }

        return $this;
    }

    /**
     * @param Person $designer
     *
     * @return $this
     */
    public function removeDesigner($designer)
    {
        $this->designers->removeElement($designer);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArtists()
    {
        return $this->artists;
    }

    /**
     * @param Person $artist
     *
     * @return $this
     */
    public function addArtist($artist)
    {
        if (!$this->artists->contains($artist)) {
            $this->artists->add($artist);
        }

        return $this;
    }

    /**
     * @param Person $artist
     *
     * @return $this
     */
    public function removeArtist($artist)
    {
        $this->artists->removeElement($artist);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPublishers()
    {
        return $this->publishers;
    }

    /**
     * @param Person $publisher
     *
     * @return $this
     */
    public function addPublisher($publisher)
    {
        if (!$this->publishers->contains($publisher)) {
            $this->publishers->add($publisher);
        }

        return $this;
    }

    /**
     * @param Person $publisher
     *
     * @return $this
     */
    public function removePublisher($publisher)
    {
        $this->publishers->removeElement($publisher);

        return $this;
    }
}
