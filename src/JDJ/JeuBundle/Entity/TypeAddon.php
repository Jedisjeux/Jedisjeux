<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeAddon
 */
class TypeAddon
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
    private $Addons;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->Addons = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return TypeAddon
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
     * Add Addons
     *
     * @param \JDJ\JeuBundle\Entity\Addon $addons
     * @return TypeAddon
     */
    public function addAddon(\JDJ\JeuBundle\Entity\Addon $addons)
    {
        $this->Addons[] = $addons;

        return $this;
    }

    /**
     * Remove Addons
     *
     * @param \JDJ\JeuBundle\Entity\Addon $addons
     */
    public function removeAddon(\JDJ\JeuBundle\Entity\Addon $addons)
    {
        $this->Addons->removeElement($addons);
    }

    /**
     * Get Addons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddons()
    {
        return $this->Addons;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $addons;


}
