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
use Sylius\Component\Product\Model\Product as BaseProduct;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product")
 */
class Product extends BaseProduct
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
    protected $intro;

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
     * @ORM\ManyToMany(targetEntity="JDJ\LudographieBundle\Entity\Personne", inversedBy="designerProducts", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="jdj_deisgner_product")
     */
    protected $designers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="JDJ\LudographieBundle\Entity\Personne", inversedBy="artistProducts", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="jdj_artist_product")
     */
    protected $artists;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="JDJ\LudographieBundle\Entity\Personne", inversedBy="publisherProducts", cascade={"persist", "merge"})
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
    }

    /**
     * @return Collection
     */
    public function getTaxons()
    {
        return $this->taxons;
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
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * @param string $intro
     * @return $this
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;

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
     * @return Collection
     */
    public function getArtists()
    {
        return $this->artists;
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
}