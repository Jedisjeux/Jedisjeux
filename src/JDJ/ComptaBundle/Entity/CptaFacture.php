<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CptaFacture
 */
class CptaFacture
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $montant;

    /**
     * @var \DateTime
     */
    private $datecreation;

    /**
     * @var \DateTime
     */
    private $datepaiement;

    /**
     * @var \JDJ\ComptaBundle\Entity\CptaClient
     */
    private $idclient;

    /**
     * @var \JDJ\ComptaBundle\Entity\ModeReglement
     */
    private $idmodereglement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $idtypeadresse;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $produit;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idtypeadresse = new \Doctrine\Common\Collections\ArrayCollection();
        $this->produit = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set montant
     *
     * @param string $montant
     * @return CptaFacture
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
     * Set datecreation
     *
     * @param \DateTime $datecreation
     * @return CptaFacture
     */
    public function setDatecreation($datecreation)
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    /**
     * Get datecreation
     *
     * @return \DateTime 
     */
    public function getDatecreation()
    {
        return $this->datecreation;
    }

    /**
     * Set datepaiement
     *
     * @param \DateTime $datepaiement
     * @return CptaFacture
     */
    public function setDatepaiement($datepaiement)
    {
        $this->datepaiement = $datepaiement;

        return $this;
    }

    /**
     * Get datepaiement
     *
     * @return \DateTime 
     */
    public function getDatepaiement()
    {
        return $this->datepaiement;
    }

    /**
     * Set idclient
     *
     * @param \JDJ\ComptaBundle\Entity\CptaClient $idclient
     * @return CptaFacture
     */
    public function setIdclient(\JDJ\ComptaBundle\Entity\CptaClient $idclient = null)
    {
        $this->idclient = $idclient;

        return $this;
    }

    /**
     * Get idclient
     *
     * @return \JDJ\ComptaBundle\Entity\CptaClient 
     */
    public function getIdclient()
    {
        return $this->idclient;
    }

    /**
     * Set idmodereglement
     *
     * @param \JDJ\ComptaBundle\Entity\ModeReglement $idmodereglement
     * @return CptaFacture
     */
    public function setIdmodereglement(\JDJ\ComptaBundle\Entity\ModeReglement $idmodereglement = null)
    {
        $this->idmodereglement = $idmodereglement;

        return $this;
    }

    /**
     * Get idmodereglement
     *
     * @return \JDJ\ComptaBundle\Entity\ModeReglement
     */
    public function getIdmodereglement()
    {
        return $this->idmodereglement;
    }

    /**
     * Add idtypeadresse
     *
     * @param \JDJ\ComptaBundle\Entity\CptaTypeadresse $idtypeadresse
     * @return CptaFacture
     */
    public function addIdtypeadresse(\JDJ\ComptaBundle\Entity\CptaTypeadresse $idtypeadresse)
    {
        $this->idtypeadresse[] = $idtypeadresse;

        return $this;
    }

    /**
     * Remove idtypeadresse
     *
     * @param \JDJ\ComptaBundle\Entity\CptaTypeadresse $idtypeadresse
     */
    public function removeIdtypeadresse(\JDJ\ComptaBundle\Entity\CptaTypeadresse $idtypeadresse)
    {
        $this->idtypeadresse->removeElement($idtypeadresse);
    }

    /**
     * Get idtypeadresse
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdtypeadresse()
    {
        return $this->idtypeadresse;
    }

    /**
     * Add produit
     *
     * @param \JDJ\ComptaBundle\Entity\Produit $produit
     * @return CptaFacture
     */
    public function addIdproduit(\JDJ\ComptaBundle\Entity\Produit $produit)
    {
        $this->produit[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \JDJ\ComptaBundle\Entity\Produit $produit
     */
    public function removeIdproduit(\JDJ\ComptaBundle\Entity\Produit $produit)
    {
        $this->produit->removeElement($produit);
    }

    /**
     * Get produit
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdproduit()
    {
        return $this->produit;
    }


    /**
     * @return string
     */
    public function __toString(){
        return $this->getId().' - '.$this->getDatepaiement()->format('d/m/Y');
    }

    /**
     * Add produit
     *
     * @param \JDJ\ComptaBundle\Entity\Produit $produit
     * @return CptaFacture
     */
    public function addProduit(\JDJ\ComptaBundle\Entity\Produit $produit)
    {
        $this->produit[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \JDJ\ComptaBundle\Entity\Produit $produit
     */
    public function removeProduit(\JDJ\ComptaBundle\Entity\Produit $produit)
    {
        $this->produit->removeElement($produit);
    }

    /**
     * Get produit
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduit()
    {
        return $this->produit;
    }
}
