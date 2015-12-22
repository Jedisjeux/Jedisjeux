<?php

namespace JDJ\LudographieBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JDJ\CoreBundle\Entity\Image;

/**
 * Personne
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonRepository")
 * @ORM\Table(name="jdj_personne", indexes={@ORM\Index(name="search_idx", columns={"slug"})})
 */
class Personne
{
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
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $siteWeb;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="JDJ\CoreBundle\Entity\Image")
     */
    private $image;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="JDJ\CoreBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinTable(name="jdj_personne_image")
     */
    private $images;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"prenom","nom"}, separator="-")
     * @ORM\Column(type="string", length=128)
     */
    private $slug;

    /**
     * @var \JDJ\WebBundle\Entity\Pays
     *
     * @ORM\ManyToOne(targetEntity="JDJ\WebBundle\Entity\Pays", inversedBy="personnes")
     */
    private $pays;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="JDJ\JeuBundle\Entity\Jeu", mappedBy="auteurs", cascade={"persist", "merge"})
     */
    private $auteurJeux;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="JDJ\JeuBundle\Entity\Jeu", mappedBy="illustrateurs", cascade={"persist", "merge"})
     */
    private $illustrateurJeux;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="JDJ\JeuBundle\Entity\Jeu", mappedBy="editeurs", cascade={"persist", "merge"})
     */
    private $editeurJeux;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->auteurJeux = new ArrayCollection();
        $this->illustrateurJeux = new ArrayCollection();
        $this->editeurJeux = new ArrayCollection();
        $this->images = new ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     * @return Personne
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Personne
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set siteWeb
     *
     * @param string $siteWeb
     * @return Personne
     */
    public function setSiteWeb($siteWeb)
    {
        $this->siteWeb = $siteWeb;

        return $this;
    }

    /**
     * Get siteWeb
     *
     * @return string 
     */
    public function getSiteWeb()
    {
        return $this->siteWeb;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Personne
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
     * Set image
     *
     * @param Image $image
     * @return Personne
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Personne
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
     * Set pays
     *
     * @param \JDJ\WebBundle\Entity\Pays $pays
     * @return Personne
     */
    public function setPays(\JDJ\WebBundle\Entity\Pays $pays = null)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return \JDJ\WebBundle\Entity\Pays 
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Add auteurJeux
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $auteurJeux
     * @return Personne
     */
    public function addAuteurJeux(\JDJ\JeuBundle\Entity\Jeu $auteurJeux)
    {
        $this->auteurJeux[] = $auteurJeux;

        return $this;
    }

    /**
     * Remove auteurJeux
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $auteurJeux
     */
    public function removeAuteurJeux(\JDJ\JeuBundle\Entity\Jeu $auteurJeux)
    {
        $this->auteurJeux->removeElement($auteurJeux);
    }

    /**
     * Get auteurJeux
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAuteurJeux()
    {
        return $this->auteurJeux;
    }

    /**
     * Add illustrateurJeux
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $illustrateurJeux
     * @return Personne
     */
    public function addIllustrateurJeux(\JDJ\JeuBundle\Entity\Jeu $illustrateurJeux)
    {
        $this->illustrateurJeux[] = $illustrateurJeux;

        return $this;
    }

    /**
     * Remove illustrateurJeux
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $illustrateurJeux
     */
    public function removeIllustrateurJeux(\JDJ\JeuBundle\Entity\Jeu $illustrateurJeux)
    {
        $this->illustrateurJeux->removeElement($illustrateurJeux);
    }

    /**
     * Get illustrateurJeux
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIllustrateurJeux()
    {
        return $this->illustrateurJeux;
    }

    /**
     * Add editeurJeux
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $editeurJeux
     * @return Personne
     */
    public function addEditeurJeux(\JDJ\JeuBundle\Entity\Jeu $editeurJeux)
    {
        $this->editeurJeux[] = $editeurJeux;

        return $this;
    }

    /**
     * Remove editeurJeux
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $editeurJeux
     */
    public function removeEditeurJeux(\JDJ\JeuBundle\Entity\Jeu $editeurJeux)
    {
        $this->editeurJeux->removeElement($editeurJeux);
    }

    /**
     * Get editeurJeux
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEditeurJeux()
    {
        return $this->editeurJeux;
    }

    /**
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param ArrayCollection $images
     * @return $this
     */
    public function setImages($images)
    {

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
         * Le prénom n'étant pas obligatoire, on enlève les espaces autour
         */
        return trim($this->getPrenom(). ' '.$this->getNom());
    }
}
