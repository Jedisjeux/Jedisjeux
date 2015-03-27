<?php

namespace JDJ\CollectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserGameAttribute
 * @package JDJ\CollectionBundle\Entity
 *
 * @ORM\Entity(repositoryClass="JDJ\CollectionBundle\Repository\UserGameAttributeRepository")
 * @ORM\Table(name="jdj_user_game_attribute")
 *
 */
class UserGameAttribute
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
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_favorite", nullable=true)
     */
    private $favorite;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_owned", nullable=true)
     */
    private $owned;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_wanted", nullable=true)
     */
    private $wanted;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="has_played", nullable=true)
     */
    private $played;

    /**
     * @var \JDJ\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="JDJ\UserBundle\Entity\User", inversedBy="userGameAttributes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \JDJ\JeuBundle\Entity\Jeu
     *
     * @ORM\ManyToOne(targetEntity="JDJ\JeuBundle\Entity\Jeu", inversedBy="userGameAttributes")
     * @ORM\JoinColumn(name="jeu_id", referencedColumnName="id")
     */
    private $jeu;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set user
     *
     * @param \JDJ\UserBundle\Entity\User $user
     * @return UserGameAttribute
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
     * Set jeu
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeu
     * @return UserGameAttribute
     */
    public function setJeu(\JDJ\JeuBundle\Entity\Jeu $jeu = null)
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
     * Set favorite
     *
     * @param boolean $favorite
     * @return UserGameAttribute
     */
    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;

        return $this;
    }

    /**
     * Get favorite
     *
     * @return boolean 
     */
    public function isFavorite()
    {
        return $this->favorite;
    }

    /**
     * Set owned
     *
     * @param boolean $owned
     * @return UserGameAttribute
     */
    public function setOwned($owned)
    {
        $this->owned = $owned;

        return $this;
    }

    /**
     * Get owned
     *
     * @return boolean 
     */
    public function isOwned()
    {
        return $this->owned;
    }

    /**
     * Set wanted
     *
     * @param boolean $wanted
     * @return UserGameAttribute
     */
    public function setWanted($wanted)
    {
        $this->wanted = $wanted;

        return $this;
    }

    /**
     * Get wanted
     *
     * @return boolean 
     */
    public function isWanted()
    {
        return $this->wanted;
    }

    /**
     * Set played
     *
     * @param boolean $played
     * @return UserGameAttribute
     */
    public function setPlayed($played)
    {
        $this->played = $played;

        return $this;
    }

    /**
     * Get played
     *
     * @return boolean 
     */
    public function hasPlayed()
    {
        return $this->played;
    }


    /**
     * Get favorite
     *
     * @return boolean 
     */
    public function getFavorite()
    {
        return $this->favorite;
    }

    /**
     * Get owned
     *
     * @return boolean 
     */
    public function getOwned()
    {
        return $this->owned;
    }

    /**
     * Get wanted
     *
     * @return boolean 
     */
    public function getWanted()
    {
        return $this->wanted;
    }

    /**
     * Get played
     *
     * @return boolean 
     */
    public function getPlayed()
    {
        return $this->played;
    }
}
