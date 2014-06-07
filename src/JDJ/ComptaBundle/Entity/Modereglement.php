<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModeReglement
 */
class ModeReglement
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
     * @return string
     */
    public function __toString(){
        return $this->getId().' - '.$this->getLibelle();
    }
}
