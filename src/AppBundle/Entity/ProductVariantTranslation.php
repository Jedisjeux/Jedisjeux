<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Product\Model\ProductVariantTranslation as BaseProductVariantTranslation;
use Sylius\Component\Resource\Model\SlugAwareInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_variant_translation")
 */
class ProductVariantTranslation extends BaseProductVariantTranslation implements SlugAwareInterface
{
    /**
     * @var null|string
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $slug;

    /**
     * @return null|string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param null|string $slug
     *
     * @return $this
     */
    public function setSlug($slug = null)
    {
        $this->slug = $slug;

        return $this;
    }
}
