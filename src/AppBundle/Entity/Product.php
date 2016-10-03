<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 07/03/2016
 * Time: 13:23
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Sylius\Component\Product\Model\Product as BaseProduct;
use Sylius\Component\Review\Model\ReviewableInterface;
use Sylius\Component\Review\Model\ReviewInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Product extends BaseProduct implements ReviewableInterface
{
    /**
     * status constants
     */
    const STATUS_NEW = "new";
    const WRITING = "WRITING";
    const NEED_A_TRANSLATION = "NEED_A_TRANSLATION";
    const NEED_A_REVIEW = "NEED_A_REVIEW";
    const READY_TO_PUBLISH = "READY_TO_PUBLISH";
    const PUBLISHED = "PUBLISHED";

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string")
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    protected $code;

    /**
     * @var ArrayCollection|TaxonInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Sylius\Component\Taxonomy\Model\TaxonInterface")
     * @ORM\JoinTable("sylius_product_taxon")
     */
    protected $taxons;

    /**
     * @var TaxonInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Taxonomy\Model\TaxonInterface")
     */
    protected $mainTaxon;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     *
     * @JMS\Expose
     * @JMS\SerializedName("min_age")
     * @JMS\Groups({"Details"})
     */
    protected $ageMin;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     *
     * @JMS\Expose
     * @JMS\SerializedName("min_player_count")
     * @JMS\Groups({"Details"})
     */
    protected $joueurMin;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     *
     * @JMS\Expose
     * @JMS\SerializedName("max_player_count")
     * @JMS\Groups({"Details"})
     */
    protected $joueurMax;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     *
     * @JMS\Expose
     * @JMS\SerializedName("min_duration")
     * @JMS\Groups({"Details"})
     */
    protected $durationMin;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     *
     * @JMS\Expose
     * @JMS\SerializedName("max_duration")
     * @JMS\Groups({"Details"})
     */
    protected $durationMax;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $durationByPlayer;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $materiel;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $but;

    /**
     * @var ArrayCollection
     */
    protected $reviews;

    /**
     * @var ArrayCollection|ProductBarcode[]
     *
     * @ORM\OneToMany(targetEntity="ProductBarcode", mappedBy="product", cascade={"persist", "merge", "remove"})
     */
    protected $barcodes;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $averageRating = 0;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->taxons = new ArrayCollection();
        $this->durationByPlayer = false;
        $this->status = self::WRITING;
        $this->reviews = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        parent::setName($name);

        /** @var ProductVariant $masterVariant */
        $masterVariant = $this->getMasterVariant();

        if ($masterVariant) {
            $masterVariant->setName($name);
        }
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
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
     *
     * @JMS\VirtualProperty
     * @JMS\Groups({"Details"})
     */
    public function getShortDescription()
    {
        return $this->translate()->getShortDescription();
    }

    /**
     * @param string $shortDescription
     *
     * @return $this
     */
    public function setShortDescription($shortDescription)
    {
        $this->translate()->setShortDescription($shortDescription);

        return $this;
    }

    /**
     * @return ProductVariantImage
     *
     * @JMS\VirtualProperty
     * @JMS\SerializedName("image")
     * @JMS\Groups({"Details"})
     */
    public function getMainImage()
    {
        return $this->getMasterVariant()->getMainImage();
    }

    /**
     * @param ProductVariantImage $mainImage
     *
     * @return $this
     */
    public function setMainImage($mainImage)
    {
        return $this->getMasterVariant()->setMainImage($mainImage);
    }

    /**
     * @return ProductVariantImage
     */
    public function getMaterialImage()
    {
        return $this->getMasterVariant()->getMaterialImage();
    }

    /**
     * @return ArrayCollection|ProductVariantImage[]
     */
    public function getImages()
    {
        return $this->getMasterVariant()->getImages();
    }

    /**
     * @return ArrayCollection|ProductVariantImage[]
     */
    public function getImagesOfAllVariants()
    {
        $collection = new ArrayCollection();

        /** @var ProductVariant $variant */
        foreach ($this->variants as $variant) {
            foreach($variant->getImages() as $image) {
                $collection->add($image);
            }
        }

        return $collection;
    }

    /**
     * @return \DateTime|null
     */
    public function getReleasedAt()
    {
        return $this->getMasterVariant()->getReleasedAt();
    }

    /**
     * @param \DateTime $releasedAt
     *
     * @return $this
     */
    public function setReleasedAt($releasedAt)
    {
        return $this->getMasterVariant()->setReleasedAt($releasedAt);
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxons($taxonomy = null)
    {
        if (null !== $taxonomy) {
            return $this->taxons->filter(function (TaxonInterface $taxon) use ($taxonomy) {
                return $taxonomy === strtolower($taxon->getRoot()->getCode());
            });
        }

        return $this->taxons->filter(function (TaxonInterface $taxon) use ($taxonomy) {
            return $taxonomy !== 'forum';
        });
    }

    /**
     * @param TaxonInterface $taxon
     *
     * @return $this
     */
    public function addTaxon(TaxonInterface $taxon)
    {
        if (!$this->taxons->contains($taxon)) {
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
     * @return TaxonInterface|Taxon
     */
    public function getMainTaxon()
    {
        return $this->mainTaxon;
    }

    /**
     * @param TaxonInterface $mainTaxon
     *
     * @return $this
     */
    public function setMainTaxon($mainTaxon)
    {
        $this->mainTaxon = $mainTaxon;

        return $this;
    }

    /**
     * @return int
     */
    public function getAgeMin()
    {
        return $this->ageMin;
    }

    /**
     * @param int $ageMin
     *
     * @return $this
     */
    public function setAgeMin($ageMin)
    {
        $this->ageMin = $ageMin;

        return $this;
    }

    /**
     * @return int
     */
    public function getJoueurMin()
    {
        return $this->joueurMin;
    }

    /**
     * @param int $joueurMin
     *
     * @return $this
     */
    public function setJoueurMin($joueurMin)
    {
        $this->joueurMin = $joueurMin;

        return $this;
    }

    /**
     * @return int
     */
    public function getJoueurMax()
    {
        return $this->joueurMax;
    }

    /**
     * @param int $joueurMax
     *
     * @return $this
     */
    public function setJoueurMax($joueurMax)
    {
        $this->joueurMax = $joueurMax;

        return $this;
    }

    /**
     * @return int
     */
    public function getDurationMin()
    {
        return $this->durationMin;
    }

    /**
     * @param int $durationMin
     *
     * @return $this
     */
    public function setDurationMin($durationMin)
    {
        $this->durationMin = $durationMin;

        return $this;
    }

    /**
     * @return int
     */
    public function getDurationMax()
    {
        return $this->durationMax;
    }

    /**
     * @param int $durationMax
     *
     * @return $this
     */
    public function setDurationMax($durationMax)
    {
        $this->durationMax = $durationMax;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isDurationByPlayer()
    {
        return $this->durationByPlayer;
    }

    /**
     * @param boolean $durationByPlayer
     *
     * @return $this
     */
    public function setDurationByPlayer($durationByPlayer)
    {
        $this->durationByPlayer = $durationByPlayer;

        return $this;
    }

    /**
     * @return string
     */
    public function getMateriel()
    {
        return $this->materiel;
    }

    /**
     * @param string $materiel
     *
     * @return $this
     */
    public function setMateriel($materiel)
    {
        $this->materiel = $materiel;

        return $this;
    }

    /**
     * @return string
     */
    public function getBut()
    {
        return $this->but;
    }

    /**
     * @param string $but
     *
     * @return $this
     */
    public function setBut($but)
    {
        $this->but = $but;

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getDesigners()
    {
        return $this->getMasterVariant()->getDesigners();
    }


    /**
     * @return Collection|Person[]
     */
    public function getArtists()
    {
        return $this->getMasterVariant()->getArtists();
    }

    /**
     * @return Collection|Person[]
     */
    public function getPublishers()
    {
        return $this->getMasterVariant()->getPublishers();
    }

    /**
     * @return ArrayCollection
     *
     * @JMS\VirtualProperty
     * @JMS\Groups({"Details"})
     * @JMS\Type("ArrayCollection<AppBundle\Entity\Taxon>")
     */
    public function getMechanisms()
    {
        return $this->getTaxons('mechanisms');
    }

    /**
     * @param TaxonInterface $mechanism
     *
     * @return $this
     */
    public function addMechanism(TaxonInterface $mechanism)
    {
        return $this->addTaxon($mechanism);
    }

    /**
     * @param TaxonInterface $mechanism
     *
     * @return $this
     */
    public function removeMechanism(TaxonInterface $mechanism)
    {
        return $this->removeTaxon($mechanism);
    }

    /**
     * @return ArrayCollection
     *
     * @JMS\VirtualProperty
     * @JMS\Groups({"Details"})
     * @JMS\Type("ArrayCollection<AppBundle\Entity\Taxon>")
     */
    public function getThemes()
    {
        return $this->getTaxons('themes');
    }

    /**
     * @param TaxonInterface $theme
     *
     * @return $this
     */
    public function addTheme(TaxonInterface $theme)
    {
        return $this->addTaxon($theme);
    }

    /**
     * @param TaxonInterface $theme
     *
     * @return $this
     */
    public function removeTheme(TaxonInterface $theme)
    {
        return $this->removeTaxon($theme);
    }

    /**
     * @return ArrayCollection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param ArrayCollection $reviews
     *
     * @return $this
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;

        return $this;
    }

    /**
     * @param ReviewInterface $review
     *
     * @return $this
     */
    public function addReview(ReviewInterface $review)
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
        }

        return $this;
    }

    /**
     * @param ReviewInterface $review
     *
     * @return $this
     */
    public function removeReview(ReviewInterface $review)
    {
        $this->reviews->remove($review);

        return $this;
    }

    /**
     * @return float
     */
    public function getAverageRating()
    {
        return $this->averageRating;
    }

    /**
     * @param float $averageRating
     *
     * @return $this
     */
    public function setAverageRating($averageRating)
    {
        $this->averageRating = $averageRating;

        return $this;
    }

    /**
     * @return ProductBarcode[]|ArrayCollection
     */
    public function getBarcodes()
    {
        return $this->barcodes;
    }

    /**
     * @param ProductBarcode $barcode
     *
     * @return $this
     */
    public function addBarcode($barcode)
    {
        if (!$this->barcodes->contains($barcode)) {
            $barcode->setProduct($this);
            $this->barcodes->add($barcode);
        }

        return $this;
    }

    /**
     * @param ProductBarcode $barcode
     *
     * @return $this
     */
    public function removeBarcode($barcode)
    {
        $this->barcodes->remove($barcode);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @JMS\VirtualProperty
     * @JMS\SerializedName("name")
     * @JMS\Groups({"Details"})
     */
    public function getName()
    {
        return parent::getName();
    }

    /**
     * {@inheritdoc}
     *
     * @JMS\VirtualProperty
     * @JMS\SerializedName("slug")
     * @JMS\Groups({"Details"})
     */
    public function getSlug()
    {
        return parent::getSlug();
    }

    /**
     * {@inheritdoc}
     *
     * @JMS\VirtualProperty
     * @JMS\SerializedName("createdAt")
     * @JMS\Groups({"Details"})
     */
    public function getCreatedAt()
    {
        return parent::getCreatedAt();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getName();
    }
}