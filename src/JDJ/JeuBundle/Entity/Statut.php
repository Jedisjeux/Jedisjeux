<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statut
 */
class Statut
{
    /**
     * @var integer
     */
    private $statut_id;

    /**
     * @var integer
     */
    private $ordre;

    /**
     * @var \JDJ\WebBundle\Entity\Statut
     */
    private $statut;


    /**
     * Set statut_id
     *
     * @param integer $statutId
     * @return Statut
     */
    public function setStatutId($statutId)
    {
        $this->statut_id = $statutId;

        return $this;
    }

    /**
     * Get statut_id
     *
     * @return integer 
     */
    public function getStatutId()
    {
        return $this->statut_id;
    }

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
