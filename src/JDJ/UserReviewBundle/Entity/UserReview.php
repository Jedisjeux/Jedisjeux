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
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \JDJ\UserReviewBundle\Entity\JeuNote
     */
    private $jeuNote;


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
     * Set jeuNote
     *
     * @param \JDJ\UserReviewBundle\Entity\JeuNote $jeuNote
     * @return UserReview
     */
    public function setJeuNote(\JDJ\UserReviewBundle\Entity\JeuNote $jeuNote)
    {
        $this->jeuNote = $jeuNote;

        return $this;
    }

    /**
     * Get jeuNote
     *
     * @return \JDJ\UserReviewBundle\Entity\JeuNote 
     */
    public function getJeuNote()
    {
        return $this->jeuNote;
    }
}
