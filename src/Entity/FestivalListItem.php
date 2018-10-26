<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table("jdj_festival_list_item")
 *
 * @UniqueEntity(
 *     fields={"list", "product"},
 *     errorPath="product",
 *     message="app.festival_list_item.product.unique"
 * )
 */
class FestivalListItem implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

    /**
     * @var FestivalList
     *
     * @ORM\ManyToOne(targetEntity="FestivalList")
     */
    protected $list;

    /**
     * @var ProductInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface")
     */
    protected $product;

    /**
     * @return FestivalList
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param FestivalList $list
     *
     * @return FestivalListItem
     */
    public function setList(FestivalList $list)
    {
        $this->list = $list;

        return $this;
    }

    /**
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param ProductInterface $product
     *
     * @return FestivalListItem
     */
    public function setProduct(ProductInterface $product)
    {
        $this->product = $product;

        return $this;
    }
}
