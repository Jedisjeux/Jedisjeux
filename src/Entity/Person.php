<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Person.
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_person", indexes={@ORM\Index(name="search_idx", columns={"slug"})})
 *
 * @JMS\ExclusionPolicy("all")
 */
class Person implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"Default"})
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     * @JMS\Groups({"Default", "Detailed"})
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"Default", "Detailed"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200, nullable=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"Detailed"})
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"Detailed"})
     */
    private $description;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"firstName","lastName"}, separator="-")
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"Default", "Detailed"})
     */
    private $slug;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $productCount = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $productCountAsDesigner = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $productCountAsArtist = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $productCountAsPublisher = 0;

    /**
     * @var PersonImage[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\PersonImage", mappedBy="person", cascade={"persist", "merge", "remove"})
     */
    protected $images;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Sylius\Component\Taxonomy\Model\TaxonInterface")
     * @ORM\JoinTable("jdj_person_taxon",
     *      inverseJoinColumns={@ORM\JoinColumn(name="taxoninterface_id", referencedColumnName="id")}
     * )
     */
    protected $taxons;

    /**
     * @var ProductInterface[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\ProductVariant", mappedBy="designers", cascade={"persist", "merge"})
     */
    private $designerProducts;

    /**
     * @var ProductInterface[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\ProductVariant", mappedBy="artists", cascade={"persist", "merge"})
     */
    private $artistProducts;

    /**
     * @var ProductInterface[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\ProductVariant", mappedBy="publishers", cascade={"persist", "merge"})
     */
    private $publisherProducts;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->designerProducts = new ArrayCollection();
        $this->artistProducts = new ArrayCollection();
        $this->publisherProducts = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->taxons = new ArrayCollection();
        $this->code = uniqid('person_');
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
     * @return PersonImage
     *
     * @deprecated
     */
    public function getMainImage()
    {
        return $this->getFirstImage();
    }

    /**
     * @return PersonImage
     *
     * @JMS\VirtualProperty
     * @JMS\SerializedName("image")
     * @JMS\Groups({"Default", "Detailed"})
     */
    public function getFirstImage(): ?PersonImage
    {
        $firstImage = $this->images->first();

        return false !== $firstImage ? $firstImage : null;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return ProductInterface[]|Collection
     */
    public function getPublisherProducts(): Collection
    {
        return $this->publisherProducts;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @param string|null $website
     */
    public function setWebsite(?string $website): void
    {
        $this->website = $website;
    }

    /**
     * @return ProductInterface[]|Collection
     */
    public function getDesignerProducts(): Collection
    {
        return $this->designerProducts;
    }

    /**
     * @return ProductInterface[]|Collection
     */
    public function getArtistProducts(): Collection
    {
        return $this->artistProducts;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return int
     */
    public function getProductCount(): int
    {
        return $this->productCount;
    }

    /**
     * @param int $productCount
     */
    public function setProductCount(int $productCount): void
    {
        $this->productCount = $productCount;
    }

    /**
     * @return int
     */
    public function getProductCountAsDesigner(): int
    {
        return $this->productCountAsDesigner;
    }

    /**
     * @param int $productCountAsDesigner
     */
    public function setProductCountAsDesigner(int $productCountAsDesigner): void
    {
        $this->productCountAsDesigner = $productCountAsDesigner;
    }

    /**
     * @return int
     */
    public function getProductCountAsArtist(): int
    {
        return $this->productCountAsArtist;
    }

    /**
     * @param int $productCountAsArtist
     */
    public function setProductCountAsArtist(int $productCountAsArtist): void
    {
        $this->productCountAsArtist = $productCountAsArtist;
    }

    /**
     * @return int
     */
    public function getProductCountAsPublisher(): int
    {
        return $this->productCountAsPublisher;
    }

    /**
     * @param int $productCountAsPublisher
     */
    public function setProductCountAsPublisher(int $productCountAsPublisher): void
    {
        $this->productCountAsPublisher = $productCountAsPublisher;
    }

    /**
     * @return PersonImage[]|Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @param PersonImage $image
     *
     * @return bool
     */
    public function hasImage(PersonImage $image): bool
    {
        return $this->images->contains($image);
    }

    /**
     * @param PersonImage $image
     */
    public function addImage(PersonImage $image): void
    {
        if (!$this->hasImage($image)) {
            $image->setPerson($this);
            $this->images->add($image);
        }
    }

    /**
     * @param PersonImage $image
     */
    public function removeImage(PersonImage $image): void
    {
        $this->images->removeElement($image);
    }

    /**
     * @param null|string $rootTaxon
     *
     * @return Collection|\Sylius\Component\Taxonomy\Model\TaxonInterface[]
     */
    public function getTaxons($rootTaxon = null): Collection
    {
        if (null !== $rootTaxon) {
            return $this->taxons->filter(function (TaxonInterface $taxon) use ($rootTaxon) {
                return $rootTaxon === strtolower($taxon->getRoot()->getCode());
            });
        }

        return $this->taxons;
    }

    /**
     * @return TaxonInterface|null
     */
    public function getZone(): ?TaxonInterface
    {
        $zones = $this->getTaxons('zones');

        return $zones->count() > 0 ? $zones->first() : null;
    }

    /**
     * @param TaxonInterface $zone
     */
    public function setZone(TaxonInterface $zone): void
    {
        if ($this->getZone()) {
            $this->removeTaxon($this->getZone());
        }

        $this->addTaxon($zone);
    }

    /**
     * @param TaxonInterface $taxon
     *
     * @return bool
     */
    public function hasTaxon(TaxonInterface $taxon): bool
    {
        return $this->taxons->contains($taxon);
    }

    /**
     * @param TaxonInterface $taxon
     */
    public function addTaxon(TaxonInterface $taxon): void
    {
        if (!$this->hasTaxon($taxon)) {
            $this->taxons->add($taxon);
        }
    }

    /**
     * @param TaxonInterface $taxon
     */
    public function removeTaxon(TaxonInterface $taxon): void
    {
        $this->taxons->removeElement($taxon);
    }

    /**
     * @return string
     *
     * @JMS\VirtualProperty
     * @JMS\SerializedName("full_name")
     * @JMS\Groups({"Default", "Detailed", "Autocomplete"})
     */
    public function getFullName(): string
    {
        if (null === $this->getFirstName()) {
            return $this->getLastName();
        }

        return $this->getFirstName().' '.$this->getLastName();
    }

    /**
     * Convert Entity To String.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getFullName();
    }
}
