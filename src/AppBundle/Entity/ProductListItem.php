<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_product_list_item")
 */
class ProductListItem implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

    /**
     * @var ProductList
     *
     * @ORM\ManyToOne(targetEntity="ProductList", inversedBy="items")
     */
    protected $list;

    /**
     * @var ProductInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface", inversedBy="listItems")
     */
    protected $product;

    /**
     * @return ProductList
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param ProductList $list
     *
     * @return $this
     */
    public function setList(ProductList $list = null)
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
     * @return $this
     */
    public function setProduct(ProductInterface $product = null)
    {
        $this->product = $product;

        return $this;
    }
}
