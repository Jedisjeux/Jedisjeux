<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeAddon
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_type_addon")
 */
class TypeAddon
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
     * @ORM\Column(type="string", nullable=false, length=50)
     */
    private $libelle;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Addon", mappedBy="typeAddon")
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
        $this->addons[] = $addons;

        return $this;
    }

    /**
     * Remove Addons
     *
     * @param \JDJ\JeuBundle\Entity\Addon $addons
     */
    public function removeAddon(\JDJ\JeuBundle\Entity\Addon $addons)
    {
        $this->addons->removeElement($addons);
    }

    /**
     * Get Addons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddons()
    {
        return $this->addons;
    }

    public function __toString()
    {
        return $this->libelle;
    }


}
