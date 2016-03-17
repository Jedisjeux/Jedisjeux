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
use JDJ\CoreBundle\Entity\Image;
use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_variant")
 */
class ProductVariant extends BaseProductVariant
{
    /**
     * @var ArrayCollection|ProductVariantImage[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductVariantImage", mappedBy="variant")
     */
    protected $images;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    protected $releasedAt;

    /**
     * @return Image
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
     * @return Image
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
     * @return ArrayCollection|ProductVariantImage[]
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param ArrayCollection|ProductVariantImage[] $images
     *
     * @return $this
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
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
