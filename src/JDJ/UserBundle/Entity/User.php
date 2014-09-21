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
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var string
     */
    private $prenom;

    /**
     * @var string
     */
    private $presentation;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var \DateTime
     */
    private $dateNaissance;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;


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
     * Set nom
     *
     * @param string $nom
     * @return User
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
     * Set prenom
     *
     * @param string $prenom
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set presentation
     *
     * @param string $presentation
     * @return User
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation
     *
     * @return string 
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return User
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return User
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
    /**
     * @var string
     */
    private $avatar;


    /**
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
}
