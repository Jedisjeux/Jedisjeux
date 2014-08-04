<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FactureProduit
 */
class FactureProduit
{
    /**
     * @var integer
     */
    private $quantite;

    /**
     * @var string
     */
    private $prixUnitaire;

    /**
     * @var \JDJ\ComptaBundle\Entity\Facture
     */
    private $facture;

    /**
     * @var \JDJ\ComptaBundle\Entity\Produit
     */
    private $produit;


    /**
     * Set quantite
     *
     * @param integer $quantite
     * @return FactureProduit
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer 
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set prixUnitaire
     *
     * @param string $prixUnitaire
     * @return FactureProduit
     */
    public function setPrixUnitaire($prixUnitaire)
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    /**
     * Get prixUnitaire
     *
     * @return string 
     */
    public function getPrixUnitaire()
    {
        return $this->prixUnitaire;
    }

    /**
     * Set facture
     *
     * @param \JDJ\ComptaBundle\Entity\Facture $facture
     * @return FactureProduit
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
     * Set produit
     *
     * @param \JDJ\ComptaBundle\Entity\Produit $produit
     * @return FactureProduit
     */
    public function setProduit(\JDJ\ComptaBundle\Entity\Produit $produit = null)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \JDJ\ComptaBundle\Entity\Produit 
     */
    public function getProduit()
    {
        return $this->produit;
    }
    /**
     * @var integer
     */
    private $facture_id;

    /**
     * @var integer
     */
    private $produit_id;


    /**
     * Set facture_id
     *
     * @param integer $factureId
     * @return FactureProduit
     */
    public function setFactureId($factureId)
    {
        $this->facture_id = $factureId;

        return $this;
    }

    /**
     * Get facture_id
     *
     * @return integer 
     */
    public function getFactureId()
    {
        return $this->facture_id;
    }

    /**
     * Set produit_id
     *
     * @param integer $produitId
     * @return FactureProduit
     */
    public function setProduitId($produitId)
    {
        $this->produit_id = $produitId;

        return $this;
    }

    /**
     * Get produit_id
     *
     * @return integer 
     */
    public function getProduitId()
    {
        return $this->produit_id;
    }
}
