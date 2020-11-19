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
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table("jdj_festival_list")
 */
class FestivalList implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

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
     * @ORM\Column(type="string")
     */
    protected $name;

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
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $startAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $endAt;

    /**
     * @var FestivalListImage|null
     *
     * @ORM\ManyToOne(targetEntity="FestivalListImage", cascade={"persist"})
     */
    protected $image;

    /**
     * @var Collection|FestivalListItem[]
     */
    protected $items;

    /**
     * FestivalList constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->code = uniqid('festival_list_');
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getStartAt(): ?\DateTime
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTime $startAt): void
    {
        $this->startAt = $startAt;
    }

    public function getEndAt(): ?\DateTime
    {
        return $this->endAt;
    }

    /**
     * @param \DateTime $endAt
     */
    public function setEndAt(?\DateTime $endAt): void
    {
        $this->endAt = $endAt;
    }

    public function getImage(): ?FestivalListImage
    {
        return $this->image;
    }

    public function setImage(?FestivalListImage $image): void
    {
        $this->image = $image;
    }

    /**
     * @return Collection|ProductInterface[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function hasItem(FestivalListItem $item): bool
    {
        return $this->items->contains($item);
    }

    public function addItem(FestivalListItem $item): void
    {
        if (!$this->hasItem($item)) {
            $this->items->add($item);
        }
    }

    public function removeItem(FestivalListItem $item): void
    {
        $this->items->removeElement($item);
    }
}
