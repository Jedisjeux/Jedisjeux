<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Caracteristique
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_caracteristique")
 */
class Caracteristique
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
     * @ORM\Column(type="string", nullable=false, length=50)
     */
    private $libelle;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JeuCaracteristique", mappedBy="caracteristique")
     */
    private $jeuCaracteristiques;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="CaracteristiqueNote", mappedBy="caracteristique")
     */
    private $caracteristiqueNotes;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jeuCaracteristiques = new ArrayCollection();
        $this->caracteristiqueNotes = new ArrayCollection();
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
     * @return Caracteristique
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
     * Add jeuCaracteristiques
     *
     * @param \JDJ\JeuBundle\Entity\JeuCaracteristique $jeuCaracteristiques
     * @return Caracteristique
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

    public function __toString()
    {
        return $this->getLibelle();
    }

    /**
     * Add caracteristiqueNotes
     *
     * @param \JDJ\JeuBundle\Entity\CaracteristiqueNote $caracteristiqueNotes
     * @return Caracteristique
     */
    public function addCaracteristiqueNote(\JDJ\JeuBundle\Entity\CaracteristiqueNote $caracteristiqueNotes)
    {
        $this->caracteristiqueNotes[] = $caracteristiqueNotes;

        return $this;
    }

    /**
     * Remove caracteristiqueNotes
     *
     * @param \JDJ\JeuBundle\Entity\CaracteristiqueNote $caracteristiqueNotes
     */
    public function removeCaracteristiqueNote(\JDJ\JeuBundle\Entity\CaracteristiqueNote $caracteristiqueNotes)
    {
        $this->caracteristiqueNotes->removeElement($caracteristiqueNotes);
    }

    /**
     * Get caracteristiqueNotes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCaracteristiqueNotes()
    {
        return $this->caracteristiqueNotes;
    }
}
