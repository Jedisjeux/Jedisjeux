<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModeReglement
 *
 * @ORM\Entity
 * @ORM\Table(name="cpta_mode_reglement")
 */
class ModeReglement
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $libelle;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Ecriture", mappedBy="modeReglement", cascade={"persist", "merge"})
     */
    private $ecritures;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->ecritures = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return ModeReglement
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
     * Add ecritures
     *
     * @param \JDJ\ComptaBundle\Entity\Ecriture $ecritures
     * @return ModeReglement
     */
    public function addEcriture(\JDJ\ComptaBundle\Entity\Ecriture $ecritures)
    {
        $this->ecritures[] = $ecritures;

        return $this;
    }

    /**
     * Remove ecritures
     *
     * @param \JDJ\ComptaBundle\Entity\Ecriture $ecritures
     */
    public function removeEcriture(\JDJ\ComptaBundle\Entity\Ecriture $ecritures)
    {
        $this->ecritures->removeElement($ecritures);
    }

    /**
     * Get ecritures
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEcritures()
    {
        return $this->ecritures;
    }

    public function __toString() {
        return $this->getLibelle();
    }
}
