<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JeuCaracteristique
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_caracteristique_jeu")
 */
class JeuCaracteristique
{
    /**
     * @var \JDJ\JeuBundle\Entity\Caracteristique
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Caracteristique", inversedBy="jeuCaracteristiques")
     */
    private $caracteristique;


    /**
     * @var \JDJ\JeuBundle\Entity\Jeu
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Jeu", inversedBy="jeuCaracteristiques")
     */
    private $jeu;

    /**
     * @var \JDJ\JeuBundle\Entity\CaracteristiqueNote
     *
     * @ORM\ManyToOne(targetEntity="CaracteristiqueNote", inversedBy="jeuCaracteristiques")
     */
    private $caracteristiqueNote;


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
