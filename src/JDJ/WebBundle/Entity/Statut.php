<?php

namespace JDJ\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statut
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_statut")
 */
class Statut
{
    const PUBLISHED = 'published';

    const INCOMPLETE = 'incomplete';

    const NEED_A_READ = 'need_a_read';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $libelle;

    /**
     * @var \JDJ\JeuBundle\Entity\Statut
     *
     * @ORM\OneToOne(targetEntity="JDJ\JeuBundle\Entity\Statut", mappedBy="statut")
     */
    private $statutJeu;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JDJ\JeuBundle\Entity\Jeu", mappedBy="statut")
     */
    private $jeux;


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
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
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
