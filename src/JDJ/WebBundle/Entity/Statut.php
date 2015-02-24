<?php

namespace JDJ\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statut
 */
class Statut
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
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
     * @return Statut
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
     * @var \JDJ\JeuBundle\Entity\Statut
     */
    private $statutJeu;


    /**
     * Set statutJeu
     *
     * @param \JDJ\JeuBundle\Entity\Statut $statutJeu
     * @return Statut
     */
    public function setStatutJeu(\JDJ\JeuBundle\Entity\Statut $statutJeu = null)
    {
        $this->statutJeu = $statutJeu;

        return $this;
    }

    /**
     * Get statutJeu
     *
     * @return \JDJ\JeuBundle\Entity\Statut 
     */
    public function getStatutJeu()
    {
        return $this->statutJeu;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $jeux;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jeux = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add jeux
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeux
     * @return Statut
     */
    public function addJeux(\JDJ\JeuBundle\Entity\Jeu $jeux)
    {
        $this->jeux[] = $jeux;

        return $this;
    }

    /**
     * Remove jeux
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeux
     */
    public function removeJeux(\JDJ\JeuBundle\Entity\Jeu $jeux)
    {
        $this->jeux->removeElement($jeux);
    }

    /**
     * Get jeux
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJeux()
    {
        return $this->jeux;
    }
}
