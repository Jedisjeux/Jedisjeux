<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Addon
 */
class Addon
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $langueAddons;

    /**
     * @var \JDJ\JeuBundle\Entity\Addon
     */
    private $jeu;

    /**
     * @var \JDJ\JeuBundle\Entity\Addon
     */
    private $typeAddon;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->langueAddons = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set url
     *
     * @param string $url
     * @return Addon
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Addon
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
     * Add langueAddons
     *
     * @param \JDJ\JeuBundle\Entity\Addon $langueAddons
     * @return Addon
     */
    public function addLangueAddon(\JDJ\JeuBundle\Entity\Addon $langueAddons)
    {
        $this->langueAddons[] = $langueAddons;

        return $this;
    }

    /**
     * Remove langueAddons
     *
     * @param \JDJ\JeuBundle\Entity\Addon $langueAddons
     */
    public function removeLangueAddon(\JDJ\JeuBundle\Entity\Addon $langueAddons)
    {
        $this->langueAddons->removeElement($langueAddons);
    }

    /**
     * Get langueAddons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLangueAddons()
    {
        return $this->langueAddons;
    }

    /**
     * Set jeu
     *
     * @param \JDJ\JeuBundle\Entity\Addon $jeu
     * @return Addon
     */
    public function setJeu(\JDJ\JeuBundle\Entity\Addon $jeu = null)
    {
        $this->jeu = $jeu;

        return $this;
    }

    /**
     * Get jeu
     *
     * @return \JDJ\JeuBundle\Entity\Addon 
     */
    public function getJeu()
    {
        return $this->jeu;
    }

    /**
     * Set typeAddon
     *
     * @param \JDJ\JeuBundle\Entity\Addon $typeAddon
     * @return Addon
     */
    public function setTypeAddon(\JDJ\JeuBundle\Entity\Addon $typeAddon = null)
    {
        $this->typeAddon = $typeAddon;

        return $this;
    }

    /**
     * Get typeAddon
     *
     * @return \JDJ\JeuBundle\Entity\Addon 
     */
    public function getTypeAddon()
    {
        return $this->typeAddon;
    }
}
