<?php

namespace JDJ\CollectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserGameAttribute
 */
class UserGameAttribute
{
    /**
     * @var tinyint
     */
    private $is_favorite;

    /**
     * @var tinyint
     */
    private $is_owned;

    /**
     * @var tinyint
     */
    private $is_wanted;

    /**
     * @var tinyint
     */
    private $has_played;

    /**
     * @var \JDJ\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \JDJ\JeuBundle\Entity\Jeu
     */
    private $jeu;


    /**
     * Set is_favorite
     *
     * @param \tinyint $isFavorite
     * @return UserGameAttribute
     */
    public function setIsFavorite(\tinyint $isFavorite)
    {
        $this->is_favorite = $isFavorite;

        return $this;
    }

    /**
     * Get is_favorite
     *
     * @return \tinyint 
     */
    public function getIsFavorite()
    {
        return $this->is_favorite;
    }

    /**
     * Set is_owned
     *
     * @param \tinyint $isOwned
     * @return UserGameAttribute
     */
    public function setIsOwned(\tinyint $isOwned)
    {
        $this->is_owned = $isOwned;

        return $this;
    }

    /**
     * Get is_owned
     *
     * @return \tinyint 
     */
    public function getIsOwned()
    {
        return $this->is_owned;
    }

    /**
     * Set is_wanted
     *
     * @param \tinyint $isWanted
     * @return UserGameAttribute
     */
    public function setIsWanted(\tinyint $isWanted)
    {
        $this->is_wanted = $isWanted;

        return $this;
    }

    /**
     * Get is_wanted
     *
     * @return \tinyint 
     */
    public function getIsWanted()
    {
        return $this->is_wanted;
    }

    /**
     * Set has_played
     *
     * @param \tinyint $hasPlayed
     * @return UserGameAttribute
     */
    public function setHasPlayed(\tinyint $hasPlayed)
    {
        $this->has_played = $hasPlayed;

        return $this;
    }

    /**
     * Get has_played
     *
     * @return \tinyint 
     */
    public function getHasPlayed()
    {
        return $this->has_played;
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
     * @var integer
     */
    private $user_id;

    /**
     * @var integer
     */
    private $jeu_id;


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
}
