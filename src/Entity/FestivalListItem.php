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
     * @return FestivalList|null
     */
    public function getList(): ?FestivalList
    {
        return $this->list;
    }

    /**
     * @param FestivalList|null $list
     */
    public function setList(?FestivalList $list): void
    {
        $this->list = $list;
    }

    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    /**
     * @param ProductInterface|null $product
     */
    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }
}
