<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 */
class Produit
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tarifs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $factures;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tarifs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->factures = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Produit
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
     * Add tarifs
     *
     * @param \JDJ\ComptaBundle\Entity\TarifProduit $tarifs
     * @return Produit
     */
    public function addTarif(\JDJ\ComptaBundle\Entity\TarifProduit $tarifs)
    {
        $this->tarifs[] = $tarifs;

        return $this;
    }

    /**
     * Remove tarifs
     *
     * @param \JDJ\ComptaBundle\Entity\TarifProduit $tarifs
     */
    public function removeTarif(\JDJ\ComptaBundle\Entity\TarifProduit $tarifs)
    {
        $this->tarifs->removeElement($tarifs);
    }

    /**
     * Get tarifs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTarifs()
    {
        return $this->tarifs;
    }

    /**
     * Add factures
     *
     * @param \JDJ\ComptaBundle\Entity\Facture $factures
     * @return Produit
     */
    public function addFacture(\JDJ\ComptaBundle\Entity\Facture $factures)
    {
        $this->factures[] = $factures;

        return $this;
    }

    /**
     * Remove factures
     *
     * @param \JDJ\ComptaBundle\Entity\Facture $factures
     */
    public function removeFacture(\JDJ\ComptaBundle\Entity\Facture $factures)
    {
        $this->factures->removeElement($factures);
    }

    /**
     * Get factures
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFactures()
    {
        return $this->factures;
    }
}
