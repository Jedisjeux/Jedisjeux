<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 07/03/2016
 * Time: 18:07
 */

namespace AppBundle\Entity;

use AppBundle\Model\Identifiable;
use Doctrine\ORM\Mapping as ORM;
use JDJ\CoreBundle\Entity\Image;
use Sylius\Component\Core\Model\ProductVariantInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_variant_image")
 */
class ProductVariantImage extends Image
{
    /**
     * The associated product variant.
     *
     * @var ProductVariantInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\VariantInterface", inversedBy="images")
     */
    protected $variant;

    /**
     * {@inheritdoc}
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * {@inheritdoc}
     */
    public function setVariant(ProductVariantInterface $variant = null)
    {
        $this->variant = $variant;

        return $this;
    }
}