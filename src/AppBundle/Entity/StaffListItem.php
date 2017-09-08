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
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 * 
 * @ORM\Entity
 * @ORM\Table("jdj_staff_list_item")
 */
class StaffListItem implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

    /**
     * @var StaffList
     *
     * @ORM\ManyToOne(targetEntity="StaffList")
     */
    protected $list;

    /**
     * @var ProductInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface")
     */
    protected $product;

    /**
     * @return StaffList
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param StaffList $list
     *
     * @return StaffListItem
     */
    public function setList(StaffList $list)
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
     * @return StaffListItem
     */
    public function setProduct(ProductInterface $product)
    {
        $this->product = $product;

        return $this;
    }
}
