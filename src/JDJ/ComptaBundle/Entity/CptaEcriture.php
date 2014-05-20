<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CptaEcriture
 */
class CptaEcriture
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
    private $datecreation;

    /**
     * @var string
     */
    private $facturefilename;

    /**
     * @var \JDJ\ComptaBundle\Entity\CptaFacture
     */
    private $idfacture;

    /**
     * @var \JDJ\ComptaBundle\Entity\CptaModereglement
     */
    private $idmodereglement;


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
     * @return CptaEcriture
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
     * @return CptaEcriture
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
     * @return CptaEcriture
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
     * @return CptaEcriture
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
     * Set facturefilename
     *
     * @param string $facturefilename
     * @return CptaEcriture
     */
    public function setFacturefilename($facturefilename)
    {
        $this->facturefilename = $facturefilename;

        return $this;
    }

    /**
     * Get facturefilename
     *
     * @return string 
     */
    public function getFacturefilename()
    {
        return $this->facturefilename;
    }

    /**
     * Set idfacture
     *
     * @param \JDJ\ComptaBundle\Entity\CptaFacture $idfacture
     * @return CptaEcriture
     */
    public function setIdfacture(\JDJ\ComptaBundle\Entity\CptaFacture $idfacture = null)
    {
        $this->idfacture = $idfacture;

        return $this;
    }

    /**
     * Get idfacture
     *
     * @return \JDJ\ComptaBundle\Entity\CptaFacture 
     */
    public function getIdfacture()
    {
        return $this->idfacture;
    }

    /**
     * Set idmodereglement
     *
     * @param \JDJ\ComptaBundle\Entity\CptaModereglement $idmodereglement
     * @return CptaEcriture
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
}
