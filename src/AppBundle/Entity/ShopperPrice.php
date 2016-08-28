<?php

/**
 * This file is part of Jedisjeux project
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_shopper_price")
 */
class ShopperPrice implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

    /**
     * @var Shopper
     *
     * @ORM\ManyToOne(entity="Shopper")
     */
    protected $shopper;

    /**
     * @var ProductVariant
     *
     * @ORM\ManyToOne(entity="ProductVariant")
     */
    protected $productVariant;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $url;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $price;

    /**
     * @return Shopper
     */
    public function getShopper()
    {
        return $this->shopper;
    }

    /**
     * @param Shopper $shopper
     *
     * @return $this
     */
    public function setShopper($shopper)
    {
        $this->shopper = $shopper;

        return $this;
    }

    /**
     * @return ProductVariant
     */
    public function getProductVariant()
    {
        return $this->productVariant;
    }

    /**
     * @param ProductVariant $productVariant
     *
     * @return $this
     */
    public function setProductVariant($productVariant)
    {
        $this->productVariant = $productVariant;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }
}
