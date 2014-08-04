<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JeuCaracteristique
 */
class JeuCaracteristique
{
    /**
     * @var integer
     */
    private $caracteristique_id;

    /**
     * @var integer
     */
    private $jeu_id;


    /**
     * Set caracteristique_id
     *
     * @param integer $caracteristiqueId
     * @return JeuCaracteristique
     */
    public function setCaracteristiqueId($caracteristiqueId)
    {
        $this->caracteristique_id = $caracteristiqueId;

        return $this;
    }

    /**
     * Get caracteristique_id
     *
     * @return integer 
     */
    public function getCaracteristiqueId()
    {
        return $this->caracteristique_id;
    }

    /**
     * Set jeu_id
     *
     * @param integer $jeuId
     * @return JeuCaracteristique
     */
    public function setJeuId($jeuId)
    {
        $this->jeu_id = $jeuId;

        return $this;
    }

    /**
     * Get jeu_id
     *
     * @return integer 
     */
    public function getJeuId()
    {
        return $this->jeu_id;
    }


    /**
     * @var \JDJ\JeuBundle\Entity\Caracteristique
     */
    private $caracteristique;


    /**
     * Set caracteristique
     *
     * @param \JDJ\JeuBundle\Entity\Caracteristique $caracteristique
     * @return JeuCaracteristique
     */
    public function setCaracteristique(\JDJ\JeuBundle\Entity\Caracteristique $caracteristique = null)
    {
        $this->caracteristique = $caracteristique;

        return $this;
    }

    /**
     * Get caracteristique
     *
     * @return \JDJ\JeuBundle\Entity\Caracteristique 
     */
    public function getCaracteristique()
    {
        return $this->caracteristique;
    }
    /**
     * @var \JDJ\JeuBundle\Entity\Jeu
     */
    private $jeu;


    /**
     * Set jeu
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeu
     * @return JeuCaracteristique
     */
    public function setJeu(\JDJ\JeuBundle\Entity\Jeu $jeu = null)
    {
        $this->jeu = $jeu;

        return $this;
    }

    /**
     * Get jeu
     *
     * @return \JDJ\JeuBundle\Entity\Jeu 
     */
    public function getJeu()
    {
        return $this->jeu;
    }
    /**
     * @var \JDJ\JeuBundle\Entity\CaracteristiqueNote
     */
    private $caracteristiqueNote;


    /**
     * Set caracteristiqueNote
     *
     * @param \JDJ\JeuBundle\Entity\CaracteristiqueNote $caracteristiqueNote
     * @return JeuCaracteristique
     */
    public function setCaracteristiqueNote(\JDJ\JeuBundle\Entity\CaracteristiqueNote $caracteristiqueNote = null)
    {
        $this->caracteristiqueNote = $caracteristiqueNote;

        return $this;
    }

    /**
     * Get caracteristiqueNote
     *
     * @return \JDJ\JeuBundle\Entity\CaracteristiqueNote 
     */
    public function getCaracteristiqueNote()
    {
        return $this->caracteristiqueNote;
    }
}
