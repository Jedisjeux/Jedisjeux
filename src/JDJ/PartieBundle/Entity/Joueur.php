<?php

namespace JDJ\PartieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Joueur
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_joueur")
 */
class Joueur
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    /**
     * @var \JDJ\PartieBundle\Entity\Partie
     *
     * @ORM\ManyToOne(targetEntity="Partie", inversedBy="joueurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $partie;

    /**
     *
     * @var \JDJ\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="JDJ\UserBundle\Entity\User")
     */
    private $user;


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
     * Set partie
     *
     * @param \JDJ\PartieBundle\Entity\Partie $partie
     * @return Joueur
     */
    public function setPartie(\JDJ\PartieBundle\Entity\Partie $partie = null)
    {
        $this->partie = $partie;

        return $this;
    }

    /**
     * Get partie
     *
     * @return \JDJ\PartieBundle\Entity\Partie 
     */
    public function getPartie()
    {
        return $this->partie;
    }


    /**
     * Set user
     *
     * @param \JDJ\UserBundle\Entity\User $user
     * @return Joueur
     */
    public function setUser(\JDJ\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \JDJ\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set nom
     *
     * @param string $nom
     * @return Joueur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set score
     *
     * @param integer $score
     * @return Joueur
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore()
    {
        return $this->score;
    }

    public function __toString()
    {
        if (null !== $this->getUser()) {
            return $this->getUser()->getUsername();
        }

        return $this->getNom();
    }
}
