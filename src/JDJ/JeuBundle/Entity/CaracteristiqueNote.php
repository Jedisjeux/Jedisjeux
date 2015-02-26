<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CaracteristiqueNote
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_note_caracteristique")
 */
class CaracteristiqueNote
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
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true})
     */
    private $valeur;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false, length=50)
     */
    private $libelle;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JeuCaracteristique", mappedBy="caracteristiqueNote")
     */
    private $jeuCaracteristiques;

    /**
     * @var \JDJ\JeuBundle\Entity\Caracteristique
     *
     * @ORM\ManyToOne(targetEntity="Caracteristique", inversedBy="caracteristiqueNotes")
     */
    private $caracteristique;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jeuCaracteristiques = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set valeur
     *
     * @param integer $valeur
     * @return CaracteristiqueNote
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return integer 
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return CaracteristiqueNote
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
     * @return CaracteristiqueNote
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
     * Set caracteristique
     *
     * @param \JDJ\JeuBundle\Entity\Caracteristique $caracteristique
     * @return CaracteristiqueNote
     */
    public function setCaracteristique(\JDJ\JeuBundle\Entity\Caracteristique $caracteristique = null)
    {
        $this->caracteristique = $caracteristique;

        return $this;
    }

    /**
     * Get caracteristique
     *
     * @return \JDJ\JeuBundle\Entity\Caracteristique 
     */
    public function getCaracteristique()
    {
        return $this->caracteristique;
    }

    public function __toString()
    {
        return $this->getValeur()."/5 - ".$this->getLibelle();
    }
}
