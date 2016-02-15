<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JDJ\CoreBundle\Entity\Image;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Jeu
 *
 * @ORM\Entity(repositoryClass="JDJ\JeuBundle\Repository\JeuRepository")
 * @ORM\Table(name="jdj_jeu", indexes={@ORM\Index(name="search_idx", columns={"slug"})})
 */
class Jeu implements ResourceInterface
{
    use TimestampableEntity;

    /**
     * status constants
     */
    const WRITING = "WRITING";
    const NEED_A_TRANSLATION = "NEED_A_TRANSLATION";
    const NEED_A_REVIEW = "NEED_A_REVIEW";
    const READY_TO_PUBLISH = "READY_TO_PUBLISH";
    const PUBLISHED = "PUBLISHED";


    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false, length=50)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     */
    private $ageMin;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     */
    private $joueurMin;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     */
    private $joueurMax;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     */
    private $durationMin;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     */
    private $durationMax;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $durationByPlayer;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $intro;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $materiel;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $but;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, separator="-")
     * @ORM\Column(type="string", length=128)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="JDJ\CoreBundle\Entity\Image")
     */
    private $imageCouverture;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="JDJ\CoreBundle\Entity\Image")
     */
    private $materialImage;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="JDJ\JeuBundle\Entity\JeuImage", cascade={"persist"}, mappedBy="jeu")
     * @ORM\JoinTable(name="jdj_jeu_image")
     */
    private $jeuImages;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Mechanism", inversedBy="jeux", cascade={"persist", "merge"})
     */
    private $mechanisms;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Theme", inversedBy="jeux", cascade={"persist", "merge"})
     */
    private $themes;
    

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="JDJ\LudographieBundle\Entity\Personne", inversedBy="auteurJeux", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="jdj_auteur_jeu")
     */
    private $auteurs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="JDJ\LudographieBundle\Entity\Personne", inversedBy="illustrateurJeux", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="jdj_illustrateur_jeu")
     */
    private $illustrateurs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="JDJ\LudographieBundle\Entity\Personne", inversedBy="editeurJeux", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="jdj_editeur_jeu")
     */
    private $editeurs;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JeuCaracteristique", mappedBy="jeu")
     */
    private $jeuCaracteristiques;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Addon", mappedBy="jeu")
     */
    private $addons;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JDJ\PartieBundle\Entity\Partie", mappedBy="jeu")
     */
    private $parties;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JDJ\UserReviewBundle\Entity\JeuNote", mappedBy="jeu")
     */
    private $notes;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string")
     */
    private $status;


    /**
     * @var \JDJ\CoreBundle\Entity\Cible
     *
     * @ORM\ManyToOne(targetEntity="JDJ\CoreBundle\Entity\Cible", inversedBy="jeux", cascade={"persist", "merge"})
     */
    private $cible;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JDJ\CollectionBundle\Entity\ListElement", mappedBy="jeu")
     */
    private $listElements;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JDJ\CollectionBundle\Entity\UserGameAttribute", mappedBy="jeu")
     */
    private $userGameAttributes;

    /**
     * @var JDJ\JediZoneBundle\Entity\Activity
     *
     * @ORM\OneToOne(targetEntity="JDJ\JediZoneBundle\Entity\Activity", mappedBy="jeu", cascade={"persist", "merge"})
     */
    private $activity;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jeuCaracteristiques = new ArrayCollection();
        $this->addons = new ArrayCollection();
        $this->mechanisms = new ArrayCollection();
        $this->themes = new ArrayCollection();
        $this->auteurs = new ArrayCollection();
        $this->illustrateurs = new ArrayCollection();
        $this->editeurs = new ArrayCollection();
        $this->parties = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->durationByPlayer = false;
    }

    /**
     * Convert Entity To String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $name
     * @return Jeu
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set ageMin
     *
     * @param integer $ageMin
     * @return Jeu
     */
    public function setAgeMin($ageMin)
    {
        $this->ageMin = $ageMin;

        return $this;
    }

    /**
     * Get ageMin
     *
     * @return integer 
     */
    public function getAgeMin()
    {
        return $this->ageMin;
    }

    /**
     * Set joueurMin
     *
     * @param integer $joueurMin
     * @return Jeu
     */
    public function setJoueurMin($joueurMin)
    {
        $this->joueurMin = $joueurMin;

        return $this;
    }

    /**
     * Get joueurMin
     *
     * @return integer 
     */
    public function getJoueurMin()
    {
        return $this->joueurMin;
    }

    /**
     * Set joueurMax
     *
     * @param integer $joueurMax
     * @return Jeu
     */
    public function setJoueurMax($joueurMax)
    {
        $this->joueurMax = $joueurMax;

        return $this;
    }

    /**
     * Get joueurMax
     *
     * @return integer 
     */
    public function getJoueurMax()
    {
        return $this->joueurMax;
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
     * Set intro
     *
     * @param string $intro
     * @return Jeu
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;

        return $this;
    }

    /**
     * Get intro
     *
     * @return string 
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * Set materiel
     *
     * @param string $materiel
     * @return Jeu
     */
    public function setMateriel($materiel)
    {
        $this->materiel = $materiel;

        return $this;
    }

    /**
     * Get materiel
     *
     * @return string 
     */
    public function getMateriel()
    {
        return $this->materiel;
    }

    /**
     * Set but
     *
     * @param string $but
     * @return Jeu
     */
    public function setBut($but)
    {
        $this->but = $but;

        return $this;
    }

    /**
     * Get but
     *
     * @return string 
     */
    public function getBut()
    {
        return $this->but;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Jeu
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
     * @return Jeu
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
     * Set imageCouverture
     *
     * @param Image $imageCouverture
     * @return Jeu
     */
    public function setImageCouverture($imageCouverture)
    {
        $this->imageCouverture = $imageCouverture;

        return $this;
    }

    /**
     * Get imageCouverture
     *
     * @return Image
     */
    public function getImageCouverture()
    {
        return $this->imageCouverture;
    }

    /**
     * @return Image
     */
    public function getMaterialImage()
    {
        return $this->materialImage;
    }

    /**
     * @param Image $materialImage
     * @return $this
     */
    public function setMaterialImage($materialImage)
    {
        $this->materialImage = $materialImage;

        return $this;
    }

    /**
     * @return JeuImage
     */
    public function getJeuImages()
    {
        return $this->jeuImages;
    }

    /**
     * Add Image
     *
     * @param JeuImage $jeuImage
     * @return Jeu
     */
    public function addJeuImage(JeuImage $jeuImage)
    {
        $this->jeuImages[] = $jeuImage;

        return $this;
    }

    /**
     * @param ArrayCollection $jeuImages
     * @return $this
     */
    public function setJeuImages($jeuImages)
    {
        $this->jeuImages = $jeuImages;

        return $this;
    }

    /**
     * Add jeuCaracteristiques
     *
     * @param \JDJ\JeuBundle\Entity\JeuCaracteristique $jeuCaracteristiques
     * @return Jeu
     */
    public function addJeuCaracteristique(\JDJ\JeuBundle\Entity\JeuCaracteristique $jeuCaracteristiques)
    {
        $this->jeuCaracteristiques[] = $jeuCaracteristiques;

        return $this;
    }

    /**
     * Remove jeuCaracteristiques
     *
     * @param \JDJ\JeuBundle\Entity\JeuCaracteristique $jeuCaracteristiques
     */
    public function removeJeuCaracteristique(\JDJ\JeuBundle\Entity\JeuCaracteristique $jeuCaracteristiques)
    {
        $this->jeuCaracteristiques->removeElement($jeuCaracteristiques);
    }

    /**
     * Get jeuCaracteristiques
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJeuCaracteristiques()
    {
        return $this->jeuCaracteristiques;
    }

    /**
     * Add addons
     *
     * @param \JDJ\JeuBundle\Entity\Addon $addons
     * @return Jeu
     */
    public function addAddon(\JDJ\JeuBundle\Entity\Addon $addons)
    {
        $this->addons[] = $addons;

        return $this;
    }

    /**
     * Remove addons
     *
     * @param \JDJ\JeuBundle\Entity\Addon $addons
     */
    public function removeAddon(\JDJ\JeuBundle\Entity\Addon $addons)
    {
        $this->addons->removeElement($addons);
    }

    /**
     * Get addons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddons()
    {
        return $this->addons;
    }

    /**
     * Set statut
     *
     * @param \JDJ\WebBundle\Entity\Statut $statut
     * @return Jeu
     */
    public function setStatut(\JDJ\WebBundle\Entity\Statut $statut = null)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return \JDJ\WebBundle\Entity\Statut 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Add mechanisms
     *
     * @param Mechanism $mecanisme
     * @return $this
     */
    public function addMechanism(\JDJ\JeuBundle\Entity\Mechanism $mecanisme)
    {
        $this->mechanisms[] = $mecanisme;

        return $this;
    }

    /**
     * Remove mechanisms
     *
     * @param Mechanism $mecanisme
     */
    public function removeMechanism(Mechanism $mecanisme)
    {
        $this->mechanisms->removeElement($mecanisme);
    }

    /**
     * Get mechanisms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMechanisms()
    {
        return $this->mechanisms;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $mechanisms
     *
     * @return $this
     */
    public function setMechanisms($mechanisms)
    {
        $this->mechanisms = $mechanisms;

        return $this;
    }

    /**
     * Add themes
     *
     * @param Theme $themes
     * @return Jeu
     */
    public function addTheme(Theme $themes)
    {
        $this->themes[] = $themes;

        return $this;
    }

    /**
     * Remove themes
     *
     * @param Theme $themes
     */
    public function removeTheme(Theme $themes)
    {
        $this->themes->removeElement($themes);
    }

    /**
     * Get themes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getThemes()
    {
        return $this->themes;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $themes
     *
     * @return $this
     */
    public function setThemes($themes)
    {
        $this->themes = $themes;

        return $this;
    }

    /**
     * Add auteurs
     *
     * @param \JDJ\LudographieBundle\Entity\Personne $auteurs
     * @return Jeu
     */
    public function addAuteur(\JDJ\LudographieBundle\Entity\Personne $auteurs)
    {
        $this->auteurs[] = $auteurs;

        return $this;
    }

    /**
     * Remove auteurs
     *
     * @param \JDJ\LudographieBundle\Entity\Personne $auteurs
     */
    public function removeAuteur(\JDJ\LudographieBundle\Entity\Personne $auteurs)
    {
        $this->auteurs->removeElement($auteurs);
    }

    /**
     * Get auteurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAuteurs()
    {
        return $this->auteurs;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $auteurs
     */
    public function setAuteurs($auteurs)
    {
        $this->auteurs = $auteurs;
    }

    /**
     * Add illustrateurs
     *
     * @param \JDJ\LudographieBundle\Entity\Personne $illustrateurs
     * @return Jeu
     */
    public function addIllustrateur(\JDJ\LudographieBundle\Entity\Personne $illustrateurs)
    {
        $this->illustrateurs[] = $illustrateurs;

        return $this;
    }

    /**
     * Remove illustrateurs
     *
     * @param \JDJ\LudographieBundle\Entity\Personne $illustrateurs
     */
    public function removeIllustrateur(\JDJ\LudographieBundle\Entity\Personne $illustrateurs)
    {
        $this->illustrateurs->removeElement($illustrateurs);
    }

    /**
     * Get illustrateurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIllustrateurs()
    {
        return $this->illustrateurs;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $illustrateurs
     */
    public function setIllustrateurs($illustrateurs)
    {
        $this->illustrateurs = $illustrateurs;
    }

    /**
     * Add editeurs
     *
     * @param \JDJ\LudographieBundle\Entity\Personne $editeurs
     * @return Jeu
     */
    public function addEditeur(\JDJ\LudographieBundle\Entity\Personne $editeurs)
    {
        $this->editeurs[] = $editeurs;

        return $this;
    }

    /**
     * Remove editeurs
     *
     * @param \JDJ\LudographieBundle\Entity\Personne $editeurs
     */
    public function removeEditeur(\JDJ\LudographieBundle\Entity\Personne $editeurs)
    {
        $this->editeurs->removeElement($editeurs);
    }

    /**
     * Get editeurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEditeurs()
    {
        return $this->editeurs;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $editeurs
     */
    public function setEditeurs($editeurs)
    {
        $this->editeurs = $editeurs;
    }

    /**
     * Add parties
     *
     * @param \JDJ\PartieBundle\Entity\Partie $parties
     * @return Jeu
     */
    public function addParty(\JDJ\PartieBundle\Entity\Partie $parties)
    {
        $this->parties[] = $parties;

        return $this;
    }

    /**
     * Remove parties
     *
     * @param \JDJ\PartieBundle\Entity\Partie $parties
     */
    public function removeParty(\JDJ\PartieBundle\Entity\Partie $parties)
    {
        $this->parties->removeElement($parties);
    }

    /**
     * Get parties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParties()
    {
        return $this->parties;
    }

    /**
     * Add notes
     *
     * @param \JDJ\UserReviewBundle\Entity\JeuNote $notes
     * @return Jeu
     */
    public function addNote(\JDJ\UserReviewBundle\Entity\JeuNote $notes)
    {
        $this->notes[] = $notes;

        return $this;
    }

    /**
     * Remove notes
     *
     * @param \JDJ\UserReviewBundle\Entity\JeuNote $notes
     */
    public function removeNote(\JDJ\UserReviewBundle\Entity\JeuNote $notes)
    {
        $this->notes->removeElement($notes);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotes()
    {
        return $this->notes;
    }
    

    /**
     * Set cible
     *
     * @param \JDJ\CoreBundle\Entity\Cible $cible
     * @return Jeu
     */
    public function setCible(\JDJ\CoreBundle\Entity\Cible $cible = null)
    {
        $this->cible = $cible;

        return $this;
    }

    /**
     * Get cible
     *
     * @return \JDJ\CoreBundle\Entity\Cible 
     */
    public function getCible()
    {
        return $this->cible;
    }



    /**
     * Add UserGameAttributes
     *
     * @param \JDJ\CollectionBundle\Entity\UserGameAttribute $userGameAttributes
     * @return Jeu
     */
    public function addUserGameAttribute(\JDJ\CollectionBundle\Entity\UserGameAttribute $userGameAttributes)
    {
        $this->UserGameAttributes[] = $userGameAttributes;

        return $this;
    }

    /**
     * Remove UserGameAttributes
     *
     * @param \JDJ\CollectionBundle\Entity\UserGameAttribute $userGameAttributes
     */
    public function removeUserGameAttribute(\JDJ\CollectionBundle\Entity\UserGameAttribute $userGameAttributes)
    {
        $this->UserGameAttributes->removeElement($userGameAttributes);
    }

    /**
     * Get UserGameAttributes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserGameAttributes()
    {
        return $this->UserGameAttributes;
    }

    /**
     * Add listElements
     *
     * @param \JDJ\CollectionBundle\Entity\listElement $listElements
     * @return Jeu
     */
    public function addListElement(\JDJ\CollectionBundle\Entity\listElement $listElements)
    {
        $this->listElements[] = $listElements;

        return $this;
    }

    /**
     * Remove listElements
     *
     * @param \JDJ\CollectionBundle\Entity\listElement $listElements
     */
    public function removeListElement(\JDJ\CollectionBundle\Entity\listElement $listElements)
    {
        $this->listElements->removeElement($listElements);
    }

    /**
     * Get listElements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getListElements()
    {
        return $this->listElements;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Jeu
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Remove jeuImages
     *
     * @param \JDJ\JeuBundle\Entity\JeuImage $jeuImages
     */
    public function removeJeuImage(\JDJ\JeuBundle\Entity\JeuImage $jeuImages)
    {
        $this->jeuImages->removeElement($jeuImages);
    }

    /**
     * This function returns the status list
     *
     * @return array
     */
    public static function getStatusList()
    {
        return array(
            self::WRITING => self::WRITING,
            self::NEED_A_REVIEW => self::NEED_A_REVIEW,
            self::NEED_A_TRANSLATION => self::NEED_A_TRANSLATION,
            self::READY_TO_PUBLISH => self::READY_TO_PUBLISH,
            self::PUBLISHED => self::PUBLISHED,
        );
    }

    /**
     * Set activity
     *
     * @param \JDJ\JediZoneBundle\Entity\Activity $activity
     *
     * @return Jeu
     */
    public function setActivity(\JDJ\JediZoneBundle\Entity\Activity $activity = null)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return \JDJ\JediZoneBundle\Entity\Activity
     */
    public function getActivity()
    {
        return $this->activity;
    }
}
