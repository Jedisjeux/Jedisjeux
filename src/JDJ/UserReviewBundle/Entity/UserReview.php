<?php

namespace JDJ\UserReviewBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserReview
 */
class UserReview
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $body;

    /**
     * @var boolean
     */
    private $commented;

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
     * Set libelle
     *
     * @param string $libelle
     * @return UserReview
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return UserReview
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set commented
     *
     * @param boolean $commented
     * @return UserReview
     */
    public function setCommented($commented)
    {
        $this->commented = $commented;

        return $this;
    }

    /**
     * Get commented
     *
     * @return boolean 
     */
    public function getCommented()
    {
        return $this->commented;
    }

    /**
     * Get commented
     *
     * @return boolean
     */
    public function isCommented()
    {
        return $this->getCommented();
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return UserReview
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
     * @return UserReview
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
     * @return UserReview
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
     * @return UserReview
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
     * @return UserReview
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
}
