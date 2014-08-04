<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facture
 */
class Facture
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $datePaiement;

    /**
     * @var string
     */
    private $montant;

    /**
     * @var \DateTime
     */
    private $dateCreation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $produits;

    /**
     * @var \JDJ\ComptaBundle\Entity\Client
     */
    private $client;

    /**
     * @var \JDJ\ComptaBundle\Entity\ModeReglement
     */
    private $modeReglement;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set datePaiement
     *
     * @param \DateTime $datePaiement
     * @return Facture
     */
    public function setDatePaiement($datePaiement)
    {
        $this->datePaiement = $datePaiement;

        return $this;
    }

    /**
     * Get datePaiement
     *
     * @return \DateTime 
     */
    public function getDatePaiement()
    {
        return $this->datePaiement;
    }

    /**
     * Set montant
     *
     * @param string $montant
     * @return Facture
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Facture
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
     * Add produits
     *
     * @param \JDJ\ComptaBundle\Entity\Produit $produits
     * @return Facture
     */
    public function addProduit(\JDJ\ComptaBundle\Entity\Produit $produits)
    {
        $this->produits[] = $produits;

        return $this;
    }

    /**
     * Remove produits
     *
     * @param \JDJ\ComptaBundle\Entity\Produit $produits
     */
    public function removeProduit(\JDJ\ComptaBundle\Entity\Produit $produits)
    {
        $this->produits->removeElement($produits);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * Set client
     *
     * @param \JDJ\ComptaBundle\Entity\Client $client
     * @return Facture
     */
    public function setClient(\JDJ\ComptaBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \JDJ\ComptaBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set modeReglement
     *
     * @param \JDJ\ComptaBundle\Entity\ModeReglement $modeReglement
     * @return Facture
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
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $factureProduits;


    /**
     * Add factureProduits
     *
     * @param \JDJ\ComptaBundle\Entity\FactureProduit $factureProduits
     * @return Facture
     */
    public function addFactureProduit(\JDJ\ComptaBundle\Entity\FactureProduit $factureProduits)
    {
        $this->factureProduits[] = $factureProduits;

        return $this;
    }

    /**
     * Remove factureProduits
     *
     * @param \JDJ\ComptaBundle\Entity\FactureProduit $factureProduits
     */
    public function removeFactureProduit(\JDJ\ComptaBundle\Entity\FactureProduit $factureProduits)
    {
        $this->factureProduits->removeElement($factureProduits);
    }

    /**
     * Get factureProduits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFactureProduits()
    {
        return $this->factureProduits;
    }
}
