<?php

namespace JDJ\UserReviewBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JeuNote
 */
class JeuNote
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \JDJ\UserBundle\Entity\User
     */
    private $author;

    /**
     * @var \JDJ\JeuBundle\Entity\Jeu
     */
    private $jeu;

    /**
     * @var \JDJ\UserReviewBundle\Entity\Note
     */
    private $note;


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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return JeuNote
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
     * @return JeuNote
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
     * Set author
     *
     * @param \JDJ\UserBundle\Entity\User $author
     * @return JeuNote
     */
    public function setAuthor(\JDJ\UserBundle\Entity\User $author)
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
     * Set jeu
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeu
     * @return JeuNote
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
     * Set note
     *
     * @param \JDJ\UserReviewBundle\Entity\Note $note
     * @return JeuNote
     */
    public function setNote(\JDJ\UserReviewBundle\Entity\Note $note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return \JDJ\UserReviewBundle\Entity\Note 
     */
    public function getNote()
    {
        return $this->note;
    }
    /**
     * @var \JDJ\UserReviewBundle\Entity\UserReview
     */
    private $userReview;


    /**
     * Set userReview
     *
     * @param \JDJ\UserReviewBundle\Entity\UserReview $userReview
     * @return JeuNote
     */
    public function setUserReview(\JDJ\UserReviewBundle\Entity\UserReview $userReview = null)
    {
        $this->userReview = $userReview;

        return $this;
    }

    /**
     * Get userReview
     *
     * @return \JDJ\UserReviewBundle\Entity\UserReview 
     */
    public function getUserReview()
    {
        return $this->userReview;
    }

    public function hasUserReview()
    {
        return (bool)$this->getUserReview();
    }
}
