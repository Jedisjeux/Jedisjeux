<?php

namespace JDJ\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping\Index;

/**
 * Cible
 *
 * @ORM\Entity(repositoryClass="JDJ\CoreBundle\Entity\CibleRepository")
 * @ORM\Table(name="jdj_cible", indexes={@ORM\Index(name="search_idx", columns={"slug"})})
 */
class Cible
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
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $libelle;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"libelle"}, separator="-")
     * @ORM\Column(type="string", length=128)
     */
    private $slug;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JDJ\JeuBundle\Entity\Jeu", mappedBy="cible", cascade={"persist", "merge"})
     */
    private $jeux;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jeux = new ArrayCollection();
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
     * @param string $libelle
     * @return Cible
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }


    /**
     * Set description
     *
     * @param string $description
     * @return Cible
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
     * @return Cible
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
     * Add jeux
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeux
     * @return Cible
     */
    public function addJeux(\JDJ\JeuBundle\Entity\Jeu $jeux)
    {
        $this->jeux[] = $jeux;

        return $this;
    }

    /**
     * Remove jeux
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeux
     */
    public function removeJeux(\JDJ\JeuBundle\Entity\Jeu $jeux)
    {
        $this->jeux->removeElement($jeux);
    }

    /**
     * Get jeux
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJeux()
    {
        return $this->jeux;
    }

    /**
     * Entity To String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }
}
