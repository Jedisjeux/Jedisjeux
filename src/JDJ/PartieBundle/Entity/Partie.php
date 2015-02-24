<?php

namespace JDJ\PartieBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Partie
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_partie")
 */
class Partie
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Joueur", mappedBy="partie", cascade={"persist"})
     */
    private $joueurs;

    /**
     * @var \JDJ\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="JDJ\UserBundle\Entity\User", inversedBy="asAuthorParties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @var \JDJ\JeuBundle\Entity\Jeu
     *
     * @ORM\ManyToOne(targetEntity="JDJ\JeuBundle\Entity\Jeu", inversedBy="parties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $jeu;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="JDJ\UserBundle\Entity\User", inversedBy="parties", cascade={"persist"})
     * @ORM\JoinTable(name="jdj_user_partie")
     */
    private $users;


    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")

     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=false)
     */
    private $playedAt;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
        $this->users = new ArrayCollection();
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
     * Add joueurs
     *
     * @param \JDJ\PartieBundle\Entity\Joueur $joueurs
     * @return Partie
     */
    public function addJoueur(\JDJ\PartieBundle\Entity\Joueur $joueurs)
    {
        $this->joueurs[] = $joueurs;

        return $this;
    }

    /**
     * Remove joueurs
     *
     * @param \JDJ\PartieBundle\Entity\Joueur $joueurs
     */
    public function removeJoueur(\JDJ\PartieBundle\Entity\Joueur $joueurs)
    {
        $this->joueurs->removeElement($joueurs);
    }

    /**
     * Get joueurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJoueurs()
    {
        return $this->joueurs;
    }

    /**
     * Set author
     *
     * @param \JDJ\UserBundle\Entity\User $author
     * @return Partie
     */
    public function setAuthor(\JDJ\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \JDJ\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }


    /**
     * Add users
     *
     * @param \JDJ\UserBundle\Entity\User $users
     * @return Partie
     */
    public function addUser(\JDJ\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \JDJ\UserBundle\Entity\User $users
     */
    public function removeUser(\JDJ\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set jeu
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeu
     * @return Partie
     */
    public function setJeu(\JDJ\JeuBundle\Entity\Jeu $jeu)
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Partie
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Partie
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set playedAt
     *
     * @param \DateTime $playedAt
     * @return Partie
     */
    public function setPlayedAt($playedAt)
    {
        $this->playedAt = $playedAt;

        return $this;
    }

    /**
     * Get playedAt
     *
     * @return \DateTime 
     */
    public function getPlayedAt()
    {
        return $this->playedAt;
    }

    public function __toString()
    {
        return $this->getId().' - '.$this->getJeu()->getLibelle();
    }
}
