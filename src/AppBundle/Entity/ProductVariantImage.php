<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 07/03/2016
 * Time: 18:07
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\ProductVariantInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_variant_image")
 */
class ProductVariantImage extends AbstractImage
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
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_main")
     */
    protected $main;

    /**
     * ProductVariantImage constructor.
     */
    public function __construct()
    {
        $this->main = false;
    }

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

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isMain()
    {
        return $this->main;
    }

    /**
     * @param boolean $main
     * @return $this
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

}