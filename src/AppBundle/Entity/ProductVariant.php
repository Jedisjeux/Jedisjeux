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
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="JDJ\CoreBundle\Entity\Image")
     */
    protected $mainImage;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductVariantImage", mappedBy="variant")
     */
    protected $images;

    /**
     * @return Image
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * @param Image $mainImage
     *
     * @return $this
     */
    public function setMainImage($mainImage)
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param ArrayCollection $images
     *
     * @return $this
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }
}