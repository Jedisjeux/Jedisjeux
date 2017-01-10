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
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_product_list")
 */
class ProductList implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

    const CODE_GAME_LIBRARY = 'game_library';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $code;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
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
     * @ORM\ManyToMany(targetEntity="Sylius\Component\Product\Model\ProductInterface")
     * @ORM\JoinTable(name="jdj_product_list_product")
     */
    protected $products;

    /**
     * ProductList constructor.
     */
    public function __construct()
    {
        $this->code = uniqid('list_');
        $this->products = new ArrayCollection();
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
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param ProductInterface $product
     *
     * @return bool
     */
    public function hasProduct(ProductInterface $product)
    {
        return $this->products->contains($product);
    }

    /**
     * @param ProductInterface $product
     *
     * @return $this
     */
    public function addProduct(ProductInterface $product)
    {
        if (!$this->hasProduct($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    /**
     * @param ProductInterface $element
     *
     * @return $this
     */
    public function removeProduct(ProductInterface $element)
    {
        $this->products->removeElement($element);

        return $this;
    }
}
