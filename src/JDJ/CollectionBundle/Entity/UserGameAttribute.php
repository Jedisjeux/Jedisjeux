<?php

namespace JDJ\CollectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserGameAttribute
 *
 * @ORM\Entity(repositoryClass="JDJ\CollectionBundle\Repository\UserGameAttributeRepository")
 * @ORM\Table(name="jdj_user_game_attribute")
 *
 */
class UserGameAttribute
{
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_favorite", nullable=false)
     */
    private $favorite;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_owned", nullable=false)
     */
    private $owned;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_wanted", nullable=false)
     */
    private $wanted;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="has_played", nullable=false)
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
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     */
    private $user_id;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     */
    private $jeu_id;


    /**
     * Constructor
     *
     * @param $favorite
     * @param $owned
     * @param $wanted
     * @param $played
     * @param $user_id
     * @param $jeu_id
     * @param $user
     * @param $jeu
     */
    function __construct($favorite, $owned, $wanted, $played, $user_id, $jeu_id, $user, $jeu)
    {
        $this->favorite = $favorite;
        $this->owned = $owned;
        $this->wanted = $wanted;
        $this->played = $played;
        $this->user_id = $user_id;
        $this->jeu_id = $jeu_id;
        $this->user = $user;
        $this->jeu = $jeu;
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
     * Set user_id
     *
     * @param integer $userId
     * @return UserGameAttribute
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set jeu_id
     *
     * @param integer $jeuId
     * @return UserGameAttribute
     */
    public function setJeuId($jeuId)
    {
        $this->jeu_id = $jeuId;

        return $this;
    }

    /**
     * Get jeu_id
     *
     * @return integer 
     */
    public function getJeuId()
    {
        return $this->jeu_id;
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
