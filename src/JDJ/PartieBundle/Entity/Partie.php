<?php

namespace JDJ\PartieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Partie
 */
class Partie
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $joueurs;

    /**
     * @var \JDJ\UserBundle\Entity\User
     */
    private $author;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->joueurs = new \Doctrine\Common\Collections\ArrayCollection();
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
}
