<?php

namespace JDJ\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Langue
 */
class Langue
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
    private $icon;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $addons;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addons = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Langue
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
     * Set icon
     *
     * @param string $icon
     * @return Langue
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Add addons
     *
     * @param \JDJ\JeuBundle\Entity\Addon $addons
     * @return Langue
     */
    public function addAddon(\JDJ\JeuBundle\Entity\Addon $addons)
    {
        $this->addons[] = $addons;

        return $this;
    }

    /**
     * Remove addons
     *
     * @param \JDJ\JeuBundle\Entity\Addon $addons
     */
    public function removeAddon(\JDJ\JeuBundle\Entity\Addon $addons)
    {
        $this->addons->removeElement($addons);
    }

    /**
     * Get addons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddons()
    {
        return $this->addons;
    }

    /**
     * Fonction toString de la classe
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }
}
