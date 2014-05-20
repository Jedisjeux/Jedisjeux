<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CptaTarifproduit
 */
class CptaTarifproduit
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $datedebut;

    /**
     * @var \DateTime
     */
    private $datefin;

    /**
     * @var string
     */
    private $prix;

    /**
     * @var \JDJ\ComptaBundle\Entity\CptaProduit
     */
    private $idproduit;


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
     * Set datedebut
     *
     * @param \DateTime $datedebut
     * @return CptaTarifproduit
     */
    public function setDatedebut($datedebut)
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    /**
     * Get datedebut
     *
     * @return \DateTime 
     */
    public function getDatedebut()
    {
        return $this->datedebut;
    }

    /**
     * Set datefin
     *
     * @param \DateTime $datefin
     * @return CptaTarifproduit
     */
    public function setDatefin($datefin)
    {
        $this->datefin = $datefin;

        return $this;
    }

    /**
     * Get datefin
     *
     * @return \DateTime 
     */
    public function getDatefin()
    {
        return $this->datefin;
    }

    /**
     * Set prix
     *
     * @param string $prix
     * @return CptaTarifproduit
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set idproduit
     *
     * @param \JDJ\ComptaBundle\Entity\CptaProduit $idproduit
     * @return CptaTarifproduit
     */
    public function setIdproduit(\JDJ\ComptaBundle\Entity\CptaProduit $idproduit = null)
    {
        $this->idproduit = $idproduit;

        return $this;
    }

    /**
     * Get idproduit
     *
     * @return \JDJ\ComptaBundle\Entity\CptaProduit 
     */
    public function getIdproduit()
    {
        return $this->idproduit;
    }
}
