<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sens
 */
class Sens
{

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ecriture;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ecriture = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param string $id
     * @return Sens
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Sens
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
     * Add ecriture
     *
     * @param \JDJ\ComptaBundle\Entity\Ecriture $ecriture
     * @return Sens
     */
    public function addEcriture(\JDJ\ComptaBundle\Entity\Ecriture $ecriture)
    {
        $this->ecriture[] = $ecriture;

        return $this;
    }

    /**
     * Remove ecriture
     *
     * @param \JDJ\ComptaBundle\Entity\Ecriture $ecriture
     */
    public function removeEcriture(\JDJ\ComptaBundle\Entity\Ecriture $ecriture)
    {
        $this->ecriture->removeElement($ecriture);
    }

    /**
     * Get ecriture
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEcriture()
    {
        return $this->ecriture;
    }

    public function __toString()
    {
        return $this->getLibelle();
    }

}
