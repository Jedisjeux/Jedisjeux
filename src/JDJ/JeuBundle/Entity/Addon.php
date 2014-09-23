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
     * Set jeu
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeu
     * @return Addon
     */
    public function setJeu(\JDJ\JeuBundle\Entity\Jeu $jeu = null)
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
     * @param \JDJ\JeuBundle\Entity\TypeAddon $typeAddon
     * @return Addon
     */
    public function setTypeAddon(\JDJ\JeuBundle\Entity\TypeAddon $typeAddon = null)
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

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $langues;


    /**
     * Add langues
     *
     * @param \JDJ\WebBundle\Entity\Langue $langues
     * @return Addon
     */
    public function addLangue(\JDJ\WebBundle\Entity\Langue $langues)
    {
        $this->langues[] = $langues;

        return $this;
    }

    /**
     * Remove langues
     *
     * @param \JDJ\WebBundle\Entity\Langue $langues
     */
    public function removeLangue(\JDJ\WebBundle\Entity\Langue $langues)
    {
        $this->langues->removeElement($langues);
    }

    /**
     * Get langues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLangues()
    {
        return $this->langues;
    }
}
