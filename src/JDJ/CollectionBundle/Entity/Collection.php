<?php

namespace JDJ\CollectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Collection
 */
class Collection
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var \JDJ\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $jeu;


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $collectionElements;

    /**
     * Constructor
     */
    public function __construct()
    {

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
     * Set name
     *
     * @param string $name
     * @return Collection
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Collection
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Collection
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
     * Set user
     *
     * @param \JDJ\UserBundle\Entity\User $user
     * @return Collection
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
     * Add jeu
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeu
     * @return Collection
     */
    public function addJeu(\JDJ\JeuBundle\Entity\Jeu $jeu)
    {
        $this->jeu[] = $jeu;

        return $this;
    }

    /**
     * Remove jeu
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeu
     */
    public function removeJeu(\JDJ\JeuBundle\Entity\Jeu $jeu)
    {
        $this->jeu->removeElement($jeu);
    }

    /**
     * Get jeu
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJeu()
    {
        return $this->jeu;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $jeux;


    /**
     * Add jeux
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeux
     * @return Collection
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



    /**
     * Add collectionElements
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $collectionElements
     * @return Collection
     */
    public function addCollectionElement(\JDJ\JeuBundle\Entity\Jeu $collectionElements)
    {
        $this->collectionElements[] = $collectionElements;

        return $this;
    }

    /**
     * Remove collectionElements
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $collectionElements
     */
    public function removeCollectionElement(\JDJ\JeuBundle\Entity\Jeu $collectionElements)
    {
        $this->collectionElements->removeElement($collectionElements);
    }

    /**
     * Get collectionElements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCollectionElements()
    {
        return $this->collectionElements;
    }
}
