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
use Sylius\Component\Product\Model\Variant;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_variant")
 */
class ProductVariant extends Variant
{
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
     * ProductVariant constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->images = new ArrayCollection();
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
}
