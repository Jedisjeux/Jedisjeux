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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_product_list")
 *
 * @JMS\ExclusionPolicy("all")
 */
class ProductList implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

    const CODE_GAME_LIBRARY = 'game_library';
    const CODE_WISHES = 'wishes';
    const CODE_SEE_LATER = 'see_later';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @JMS\Expose
     * @JMS\Groups({"Default"})
     */
    protected $code;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @JMS\Expose
     * @JMS\Groups({"Default"})
     */
    protected $name;

    /**
     * @var CustomerInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Customer\Model\CustomerInterface")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $owner;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ProductListItem", mappedBy="list", cascade={"all"})
     */
    protected $items;

    /**
     * ProductList constructor.
     */
    public function __construct()
    {
        $this->code = uniqid('list_');
        $this->items = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return CustomerInterface
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param CustomerInterface $owner
     */
    public function setOwner(CustomerInterface $owner = null)
    {
        $this->owner = $owner;
    }

    /**
     * @return Collection|ProductInterface[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ProductListItem $item
     *
     * @return bool
     */
    public function hasItem(ProductListItem $item)
    {
        return $this->items->contains($item);
    }

    /**
     * @param ProductListItem $item
     *
     * @return $this
     */
    public function addItem(ProductListItem $item)
    {
        if (!$this->hasItem($item)) {
            $item->setList($this);
            $this->items->add($item);
        }

        return $this;
    }

    /**
     * @param ProductListItem $item
     *
     * @return $this
     */
    public function removeItem(ProductListItem $item)
    {
        $this->items->removeElement($item);

        return $this;
    }

    /**
     * @return ProductInterface|null
     */
    public function getLastProduct()
    {
        /** @var ProductListItem $lastItem */
        $lastItem = $this->items->last();

        return false !== $lastItem ? $lastItem->getProduct() : null;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getName();
    }
}
