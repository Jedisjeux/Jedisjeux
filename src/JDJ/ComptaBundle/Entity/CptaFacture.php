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
     * @var \JDJ\ComptaBundle\Entity\CptaModereglement
     */
    private $idmodereglement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $idtypeadresse;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $idproduit;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idtypeadresse = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idproduit = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \JDJ\ComptaBundle\Entity\CptaModereglement $idmodereglement
     * @return CptaFacture
     */
    public function setIdmodereglement(\JDJ\ComptaBundle\Entity\CptaModereglement $idmodereglement = null)
    {
        $this->idmodereglement = $idmodereglement;

        return $this;
    }

    /**
     * Get idmodereglement
     *
     * @return \JDJ\ComptaBundle\Entity\CptaModereglement 
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
     * Add idproduit
     *
     * @param \JDJ\ComptaBundle\Entity\Produit $idproduit
     * @return CptaFacture
     */
    public function addIdproduit(\JDJ\ComptaBundle\Entity\Produit $idproduit)
    {
        $this->idproduit[] = $idproduit;

        return $this;
    }

    /**
     * Remove idproduit
     *
     * @param \JDJ\ComptaBundle\Entity\Produit $idproduit
     */
    public function removeIdproduit(\JDJ\ComptaBundle\Entity\Produit $idproduit)
    {
        $this->idproduit->removeElement($idproduit);
    }

    /**
     * Get idproduit
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdproduit()
    {
        return $this->idproduit;
    }


    /**
     * @return string
     */
    public function __toString(){
        return $this->getId().' - '.$this->getDatepaiement()->format('d/m/Y');
    }
}
