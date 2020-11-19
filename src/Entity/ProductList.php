<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;

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
     *
     * @Assert\NotBlank()
     *
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
     * @ORM\OrderBy({"createdAt" = "ASC"})
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getOwner(): ?CustomerInterface
    {
        return $this->owner;
    }

    public function setOwner(?CustomerInterface $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return Collection|ProductInterface[]
     */
    public function getItems(): ?Collection
    {
        return $this->items;
    }

    public function hasItem(ProductListItem $item): bool
    {
        return $this->items->contains($item);
    }

    public function addItem(ProductListItem $item): void
    {
        if (!$this->hasItem($item)) {
            $item->setList($this);
            $this->items->add($item);
        }
    }

    public function removeItem(ProductListItem $item): void
    {
        $this->items->removeElement($item);
    }

    public function getLastProduct(): ?ProductInterface
    {
        /** @var ProductListItem $lastItem */
        $lastItem = $this->items->last();

        return false !== $lastItem ? $lastItem->getProduct() : null;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->getName();
    }
}
