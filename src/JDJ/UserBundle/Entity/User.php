<?php

namespace JDJ\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $asAuthorParties;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $parties;


    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->asAuthorParties = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parties = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add asAuthorParties
     *
     * @param \JDJ\PartieBundle\Entity\Partie $asAuthorParties
     * @return User
     */
    public function addAsAuthorParty(\JDJ\PartieBundle\Entity\Partie $asAuthorParties)
    {
        $this->asAuthorParties[] = $asAuthorParties;

        return $this;
    }

    /**
     * Remove asAuthorParties
     *
     * @param \JDJ\PartieBundle\Entity\Partie $asAuthorParties
     */
    public function removeAsAuthorParty(\JDJ\PartieBundle\Entity\Partie $asAuthorParties)
    {
        $this->asAuthorParties->removeElement($asAuthorParties);
    }

    /**
     * Get asAuthorParties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAsAuthorParties()
    {
        return $this->asAuthorParties;
    }

    /**
     * Add parties
     *
     * @param \JDJ\PartieBundle\Entity\Partie $parties
     * @return User
     */
    public function addParty(\JDJ\PartieBundle\Entity\Partie $parties)
    {
        $this->parties[] = $parties;

        return $this;
    }

    /**
     * Remove parties
     *
     * @param \JDJ\PartieBundle\Entity\Partie $parties
     */
    public function removeParty(\JDJ\PartieBundle\Entity\Partie $parties)
    {
        $this->parties->removeElement($parties);
    }

    /**
     * Get parties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParties()
    {
        return $this->parties;
    }
}
