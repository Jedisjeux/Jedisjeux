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
use JDJ\CoreBundle\Entity\Image;
use Sylius\Component\Product\Model\Product as BaseProduct;
use Sylius\Component\Review\Model\ReviewableInterface;
use Sylius\Component\Review\Model\ReviewInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product")
 */
class Product extends BaseProduct implements ReviewableInterface
{
    /**
     * status constants
     */
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
     * @ORM\Column(type="string", unique=true)
     */
    protected $code;

    /**
     * @var Collection
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
     */
    protected $ageMin;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     */
    protected $joueurMin;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     */
    protected $joueurMax;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     */
    protected $durationMin;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Person", inversedBy="designerProducts", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="jdj_designer_product")
     */
    protected $designers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Person", inversedBy="artistProducts", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="jdj_artist_product")
     */
    protected $artists;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Person", inversedBy="publisherProducts", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="jdj_publisher_product")
     */
    protected $publishers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JDJ\JeuBundle\Entity\Addon", mappedBy="jeu")
     */
    protected $addons;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JDJ\PartieBundle\Entity\Partie", mappedBy="jeu")
     */
    protected $parties;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JDJ\CollectionBundle\Entity\ListElement", mappedBy="jeu")
     */
    protected $listElements;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JDJ\CollectionBundle\Entity\UserGameAttribute", mappedBy="jeu")
     */
    protected $userGameAttributes;

    /**
     * @var ArrayCollection
     */
    protected $reviews;

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
        $this->userGameAttributes = new ArrayCollection();
        $this->listElements = new ArrayCollection();
        $this->designers = new ArrayCollection();
        $this->artists = new ArrayCollection();
        $this->publishers = new ArrayCollection();
        $this->parties = new ArrayCollection();
        $this->addons = new ArrayCollection();
        $this->taxons = new ArrayCollection();
        $this->durationByPlayer = false;
        $this->status = self::WRITING;
        $this->reviews = new ArrayCollection();
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
    public function getShortDescription()
    {
        return $this->translate()->getShortDescription();
    }

    /**
     * @param string $shortDescription
     * @return $this
     */
    public function setShortDescription($shortDescription)
    {
        $this->translate()->setShortDescription($shortDescription);

        return $this;
    }

    /**
     * @return Image
     */
    public function getMainImage()
    {
        return $this->getMasterVariant()->getMainImage();
    }

    /**
     * @param Image $mainImage
     * @return $this
     */
    public function setMainImage($mainImage)
    {
        return $this->getMasterVariant()->setMainImage($mainImage);
    }

    /**
     * @return Image
     */
    public function getMaterialImage()
    {
        return $this->getMasterVariant()->getMaterialImage();
    }

    /**
     * @deprecated
     */
    public function getImageCouverture()
    {
        return $this->getMainImage();
    }

    /**
     * @deprecated
     */
    public function setImageCouverture($imageCouverture)
    {
        return $this->setMainImage($imageCouverture);
    }

    public function getImages()
    {
        return $this->getMasterVariant()->getImages();
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
                return $taxonomy === strtolower($taxon->getTaxonomy()->getCode());
            });
        }

        return $this->taxons->filter(function (TaxonInterface $taxon) use ($taxonomy) {
            return $taxonomy !== 'forum';
        });
    }

    /**
     * @param Collection $taxons
     * @return $this
     */
    public function setTaxons($taxons)
    {
        $this->taxons = $taxons;

        return $this;
    }

    /**
     * @return TaxonInterface
     */
    public function getMainTaxon()
    {
        return $this->mainTaxon;
    }

    /**
     * @param TaxonInterface $mainTaxon
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
     * @return $this
     */
    public function setBut($but)
    {
        $this->but = $but;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getDesigners()
    {
        return $this->designers;
    }

    /**
     * @param Collection $designers
     * @return $this
     */
    public function setDesigners($designers)
    {
        $this->designers = $designers;

        return $this;
    }

    /**
     * @deprecated
     */
    public function getAuteurs()
    {
        return $this->getDesigners();
    }

    /**
     * @return Collection
     */
    public function getArtists()
    {
        return $this->artists;
    }

    /**
     * @deprecated
     */
    public function getIllustrateurs()
    {
        return $this->getArtists();
    }

    /**
     * @param Collection $artists
     * @return $this
     */
    public function setArtists($artists)
    {
        $this->artists = $artists;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPublishers()
    {
        return $this->publishers;
    }

    /**
     * @deprecated
     */
    public function getEditeurs()
    {
        return $this->getPublishers();
    }

    /**
     * @param Collection $publishers
     * @return $this
     */
    public function setPublishers($publishers)
    {
        $this->publishers = $publishers;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAddons()
    {
        return $this->addons;
    }

    /**
     * @param Collection $addons
     * @return $this
     */
    public function setAddons($addons)
    {
        $this->addons = $addons;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getParties()
    {
        return $this->parties;
    }

    /**
     * @param Collection $parties
     * @return $this
     */
    public function setParties($parties)
    {
        $this->parties = $parties;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getListElements()
    {
        return $this->listElements;
    }

    /**
     * @param Collection $listElements
     * @return $this
     */
    public function setListElements($listElements)
    {
        $this->listElements = $listElements;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getUserGameAttributes()
    {
        return $this->userGameAttributes;
    }

    /**
     * @param Collection $userGameAttributes
     * @return $this
     */
    public function setUserGameAttributes($userGameAttributes)
    {
        $this->userGameAttributes = $userGameAttributes;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMechanisms()
    {
        return $this->getTaxons('mechanisms');
    }

    /**
     * @deprecated
     */
    public function jeuImages()
    {
        return $this->getImages();
    }

    /**
     * @return ArrayCollection
     */
    public function getThemes()
    {
        return $this->getTaxons('themes');
    }

    public function __toString()
    {
        return $this->getName();
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
}