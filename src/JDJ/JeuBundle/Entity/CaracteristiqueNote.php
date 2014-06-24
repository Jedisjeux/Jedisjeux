<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CaracteristiqueNote
 */
class CaracteristiqueNote
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $valeur;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $jeuCaracteristiques;

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
     * @var \JDJ\JeuBundle\Entity\Caracteristique
     */
    private $caracteristique;


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
