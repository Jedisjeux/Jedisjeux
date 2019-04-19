<?php

/**
 * This file is part of Jedisjeux project.
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
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_dealer")
 */
class Dealer implements ResourceInterface
{
    use IdentifiableTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var DealerImage
     *
     * @ORM\OneToOne(targetEntity="DealerImage", cascade={"persist"})
     *
     * @Assert\Type(type="App\Entity\DealerImage")
     * @Assert\Valid()
     */
    protected $image;

    /**
     * @var PriceList
     *
     * @ORM\OneToOne(targetEntity="PriceList", mappedBy="dealer", cascade={"persist", "merge", "remove"})
     *
     * @Assert\Type(type="App\Entity\PriceList")
     * @Assert\Valid()
     */
    protected $priceList;

    /**
     * @var ArrayCollection|PubBanner[]
     *
     * @ORM\OneToMany(targetEntity="PubBanner", mappedBy="dealer", orphanRemoval=true, cascade={"persist", "merge", "remove"})
     */
    protected $pubBanners;

    /**
     * @var Collection|DealerContact[]
     *
     * @ORM\OneToMany(targetEntity="DealerContact", mappedBy="dealer", orphanRemoval=true, cascade={"persist", "merge", "remove"})
     */
    protected $contacts;

    /**
     * Dealer constructor.
     */
    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->pubBanners = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return DealerImage|null
     */
    public function getImage(): ?DealerImage
    {
        return $this->image;
    }

    /**
     * @param DealerImage $image
     */
    public function setImage(?DealerImage $image): void
    {
        $this->image = $image;
    }

    /**
     * @return bool
     */
    public function hasPriceList(): bool
    {
        return null !== $this->priceList;
    }

    /**
     * @return PriceList|null
     */
    public function getPriceList(): ?PriceList
    {
        return $this->priceList;
    }

    /**
     * @param PriceList|null $priceList
     */
    public function setPriceList(?PriceList $priceList): void
    {
        $priceList->setDealer($this);
        $this->priceList = $priceList;
    }

    /**
     * @return PubBanner[]|Collection
     */
    public function getPubBanners(): Collection
    {
        return $this->pubBanners;
    }

    /**
     * @param PubBanner $pubBanner
     *
     * @return bool
     */
    public function hasPubBanner(PubBanner $pubBanner): bool
    {
        return $this->pubBanners->contains($pubBanner);
    }

    /**
     * @param PubBanner $pubBanner
     */
    public function addPubBanner(PubBanner $pubBanner): void
    {
        if (!$this->hasPubBanner($pubBanner)) {
            $pubBanner->setDealer($this);
            $this->pubBanners->add($pubBanner);
        }
    }

    /**
     * @param PubBanner $pubBanner
     */
    public function removePubBanner(PubBanner $pubBanner): void
    {
        $this->pubBanners->removeElement($pubBanner);
        $pubBanner->setDealer(null);
    }

    /**
     * @return DealerContact[]|Collection
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    /**
     * @param DealerContact $contact
     *
     * @return bool
     */
    public function hasContact(DealerContact $contact): bool
    {
        return $this->contacts->contains($contact);
    }

    /**
     * @param DealerContact $contact
     */
    public function addContact(DealerContact $contact): void
    {
        if (!$this->hasContact($contact)) {
            $contact->setDealer($this);
            $this->contacts->add($contact);
        }
    }

    /**
     * @param DealerContact $contact
     */
    public function removeContact(DealerContact $contact): void
    {
        $this->contacts->removeElement($contact);
        $contact->setDealer(null);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return (string) $this->getName();
    }
}
