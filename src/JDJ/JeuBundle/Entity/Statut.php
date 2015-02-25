<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statut
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_statut_jeu")
 */
class Statut
{
    /**
     * @var \JDJ\WebBundle\Entity\Statut
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="JDJ\WebBundle\Entity\Statut", inversedBy="statutJeu", cascade={"persist"})
     */
    private $statut;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true})
     */
    private $ordre;

    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return Statut
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer 
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set statut
     *
     * @param \JDJ\WebBundle\Entity\Statut $statut
     * @return Statut
     */
    public function setStatut(\JDJ\WebBundle\Entity\Statut $statut = null)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return \JDJ\WebBundle\Entity\Statut 
     */
    public function getStatut()
    {
        return $this->statut;
    }
    /**
     * @var integer
     */
    private $statutJeu;


    /**
     * Set statutJeu
     *
     * @param integer $statutJeu
     * @return Statut
     */
    public function setStatutJeu($statutJeu)
    {
        $this->statutJeu = $statutJeu;

        return $this;
    }

    /**
     * Get statutJeu
     *
     * @return integer 
     */
    public function getStatutJeu()
    {
        return $this->statutJeu;
    }
}
