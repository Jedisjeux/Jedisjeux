<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
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

    /**
     * @param string $code
     *
     * @return FestivalList
     */
    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
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
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @param string $slug
     *
     * @return FestivalList
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param \DateTime $startAt
     *
     * @return $this
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * @param \DateTime $endAt
     *
     * @return $this
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * @return FestivalListImage|null
     */
    public function getImage(): ?FestivalListImage
    {
        return $this->image;
    }

    /**
     * @param FestivalListImage|null $image
     */
    public function setImage(?FestivalListImage $image): void
    {
        $this->image = $image;
    }

    /**
     * @return Collection|ProductInterface[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param FestivalListItem $item
     *
     * @return bool
     */
    public function hasItem(FestivalListItem $item)
    {
        return $this->items->contains($item);
    }

    /**
     * @param FestivalListItem $item
     *
     * @return $this
     */
    public function addItem(FestivalListItem $item)
    {
        if (!$this->hasItem($item)) {
            $this->items->add($item);
        }

        return $this;
    }

    /**
     * @param FestivalListItem $item
     *
     * @return $this
     */
    public function removeItem(FestivalListItem $item)
    {
        $this->items->removeElement($item);

        return $this;
    }
}
