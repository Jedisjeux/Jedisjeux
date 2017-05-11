<?php

namespace AppBundle\Entity;

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
 * Person
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
     * @ORM\Column(type="string", unique=true, nullable=true)
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
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $productCountAsDesigner = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $productCountAsArtist = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $productCountAsPublisher = 0;

    /**
     * @var PersonImage[]|Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PersonImage", mappedBy="person", cascade={"persist", "merge"})
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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ProductVariant", mappedBy="designers", cascade={"persist", "merge"})
     */
    private $designerProducts;

    /**
     * @var ProductInterface[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ProductVariant", mappedBy="artists", cascade={"persist", "merge"})
     */
    private $artistProducts;

    /**
     * @var ProductInterface[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ProductVariant", mappedBy="publishers", cascade={"persist", "merge"})
     */
    private $publisherProducts;

    /**
     * Constructor
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
    public function getFirstImage()
    {
        $firstImage = $this->images->first();

        return false !== $firstImage ? $firstImage : null;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return ProductInterface[]|Collection
     */
    public function getPublisherProducts()
    {
        return $this->publisherProducts;
    }

    /**
     * @param ProductInterface[]|Collection $publisherProducts
     *
     * @return $this
     */
    public function setPublisherProducts($publisherProducts)
    {
        $this->publisherProducts = $publisherProducts;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $website
     *
     * @return $this
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * @return ProductInterface[]|Collection
     */
    public function getDesignerProducts()
    {
        return $this->designerProducts;
    }

    /**
     * @param ProductInterface[]|Collection $designerProducts
     *
     * @return $this
     */
    public function setDesignerProducts($designerProducts)
    {
        $this->designerProducts = $designerProducts;

        return $this;
    }

    /**
     * @return ProductInterface[]|Collection
     */
    public function getArtistProducts()
    {
        return $this->artistProducts;
    }

    /**
     * @param ProductInterface[]|Collection $artistProducts
     *
     * @return $this
     */
    public function setArtistProducts($artistProducts)
    {
        $this->artistProducts = $artistProducts;

        return $this;
    }

    /**
     * Set description
     *
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
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
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
     *
     * @return $this
     */
    public function setProductCountAsDesigner($productCountAsDesigner)
    {
        $this->productCountAsDesigner = $productCountAsDesigner;

        return $this;
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
     *
     * @return $this
     */
    public function setProductCountAsArtist($productCountAsArtist)
    {
        $this->productCountAsArtist = $productCountAsArtist;

        return $this;
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
     *
     * @return $this
     */
    public function setProductCountAsPublisher($productCountAsPublisher)
    {
        $this->productCountAsPublisher = $productCountAsPublisher;

        return $this;
    }

    /**
     * @return PersonImage[]|Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param PersonImage $image
     *
     * @return bool
     */
    public function hasImage(PersonImage $image)
    {
        return $this->images->contains($image);
    }

    /**
     * @param PersonImage $image
     *
     * @return $this
     */
    public function addImage(PersonImage $image)
    {
        if (!$this->hasImage($image)) {
            $image->setPerson($this);
            $this->images->add($image);
        }

        return $this;
    }

    /**
     * @param PersonImage $image
     *
     * @return $this
     */
    public function removeImage(PersonImage $image)
    {
        $this->images->removeElement($image);

        return $this;
    }

    /**
     * @param null|string $rootTaxon
     *
     * @return Collection|\Sylius\Component\Taxonomy\Model\TaxonInterface[]
     */
    public function getTaxons($rootTaxon = null)
    {
        if (null !== $rootTaxon) {
            return $this->taxons->filter(function (TaxonInterface $taxon) use ($rootTaxon) {
                return $rootTaxon === strtolower($taxon->getRoot()->getCode());
            });
        }

        return $this->taxons;
    }

    /**
     * @return TaxonInterface
     */
    public function getZone()
    {
        $zones = $this->getTaxons('zones');
        return $zones->count() > 0 ? $zones->first() : null;
    }

    /**
     * @param TaxonInterface $zone
     *
     * @return $this
     */
    public function setZone(TaxonInterface $zone)
    {
        if ($this->getZone()) {
            $this->removeTaxon($this->getZone());
        }

        $this->addTaxon($zone);

        return $this;
    }

    /**
     * @param TaxonInterface $taxon
     *
     * @return bool
     */
    public function hasTaxon(TaxonInterface $taxon)
    {
        return $this->taxons->contains($taxon);
    }

    /**
     * @param TaxonInterface $taxon
     *
     * @return $this
     */
    public function addTaxon(TaxonInterface $taxon)
    {
        if (!$this->hasTaxon($taxon)) {
            $this->taxons->add($taxon);
        }

        return $this;
    }

    /**
     * @param TaxonInterface $taxon
     *
     * @return $this
     */
    public function removeTaxon(TaxonInterface $taxon)
    {
        $this->taxons->removeElement($taxon);

        return $this;
    }

    /**
     * @return string
     *
     * @JMS\VirtualProperty
     * @JMS\SerializedName("full_name")
     * @JMS\Groups({"Default", "Detailed", "Autocomplete"})
     */
    public function getFullName()
    {
        if (null === $this->getFirstName()) {
            return $this->getLastName();
        }

        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    /**
     * Convert Entity To String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getFullName();
    }
}
