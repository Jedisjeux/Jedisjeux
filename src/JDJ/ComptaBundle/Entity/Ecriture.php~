<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ecriture
 */
class Ecriture
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $sens;

    /**
     * @var string
     */
    private $montant;

    /**
     * @var \DateTime
     */
    private $dateEcriture;

    /**
     * @var \DateTime
     */
    private $dateCreation;

    /**
     * @var string
     */
    private $factureFilename;

    /**
     * @var \JDJ\ComptaBundle\Entity\Facture
     */
    private $facture;

    /**
     * @var \JDJ\ComptaBundle\Entity\ModeReglement
     */
    private $modeReglement;


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
     * @return Ecriture
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
     * Set sens
     *
     * @param string $sens
     * @return Ecriture
     */
    public function setSens($sens)
    {
        $this->sens = $sens;

        return $this;
    }

    /**
     * Get sens
     *
     * @return string 
     */
    public function getSens()
    {
        return $this->sens;
    }

    /**
     * Set montant
     *
     * @param string $montant
     * @return Ecriture
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set dateEcriture
     *
     * @param \DateTime $dateEcriture
     * @return Ecriture
     */
    public function setDateEcriture($dateEcriture)
    {
        $this->dateEcriture = $dateEcriture;

        return $this;
    }

    /**
     * Get dateEcriture
     *
     * @return \DateTime 
     */
    public function getDateEcriture()
    {
        return $this->dateEcriture;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Ecriture
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set factureFilename
     *
     * @param string $factureFilename
     * @return Ecriture
     */
    public function setFactureFilename($factureFilename)
    {
        $this->factureFilename = $factureFilename;

        return $this;
    }

    /**
     * Get factureFilename
     *
     * @return string 
     */
    public function getFactureFilename()
    {
        return $this->factureFilename;
    }

    /**
     * Set facture
     *
     * @param \JDJ\ComptaBundle\Entity\Facture $facture
     * @return Ecriture
     */
    public function setFacture(\JDJ\ComptaBundle\Entity\Facture $facture = null)
    {
        $this->facture = $facture;

        return $this;
    }

    /**
     * Get facture
     *
     * @return \JDJ\ComptaBundle\Entity\Facture 
     */
    public function getFacture()
    {
        return $this->facture;
    }

    /**
     * Set modeReglement
     *
     * @param \JDJ\ComptaBundle\Entity\ModeReglement $modeReglement
     * @return Ecriture
     */
    public function setModeReglement(\JDJ\ComptaBundle\Entity\ModeReglement $modeReglement = null)
    {
        $this->modeReglement = $modeReglement;

        return $this;
    }

    /**
     * Get modeReglement
     *
     * @return \JDJ\ComptaBundle\Entity\ModeReglement 
     */
    public function getModeReglement()
    {
        return $this->modeReglement;
    }
}
