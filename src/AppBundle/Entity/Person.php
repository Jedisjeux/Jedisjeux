<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * Person
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonRepository")
 * @ORM\Table(name="jdj_person", indexes={@ORM\Index(name="search_idx", columns={"slug"})})
 *
 * @JMS\ExclusionPolicy("all")
 */
class Person implements ResourceInterface
{
    use IdentifiableTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     *
     * @JMS\Expose
     * @JMS\Groups({"Default", "Detailed"})
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
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
     * @var PersonImage[]|Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PersonImage", mappedBy="person", cascade={"persist", "merge"})
     */
    protected $images;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Sylius\Component\Taxonomy\Model\TaxonInterface")
     * @ORM\JoinTable("jdj_person_taxon")
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
    }

    /**
     * @return PersonImage
     */
    public function getMainImage()
    {
        foreach ($this->images as $image) {
            if ($image->isMain()) {
                return $image;
            }
        }

        return null;
    }

    /**
     * @deprecated use getMainImage instead
     */
    public function getImage()
    {
        return null;
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
     * @return PersonImage[]|Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param PersonImage[]|Collection $images
     *
     * @return $this
     */
    public function setImages($images)
    {
        $this->images = $images;

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
     * @return ArrayCollection
     */
    public function getZone()
    {
        $zones = $this->getTaxons('zones');
        return $zones->count() > 0 ? $zones->first() : null;
    }

    /**
     * @param Collection $taxons
     *
     * @return $this
     */
    public function setTaxons($taxons)
    {
        $this->taxons = $taxons;

        return $this;
    }

    /**
     * Convert Entity To String
     *
     * @return string
     */
    public function __toString()
    {
        /**
         * firstname is not mandatory, thus we trim concatenation
         */
        return trim($this->getFirstName() . ' ' . $this->getLastName());
    }
}
