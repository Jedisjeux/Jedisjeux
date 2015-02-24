<?php

namespace JDJ\UserReviewBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Gedmo\Mapping\Annotation as Gedmo;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * JeuNote
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_jeu_note", indexes={@ORM\Index(name="search_idx", columns={"jeu_id", "author_id"})})
 * @UniqueEntity({"jeu", "author"})
 *
 */
class JeuNote
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
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var \JDJ\UserReviewBundle\Entity\UserReview
     *
     * @ORM\OneToOne(targetEntity="UserReview", mappedBy="jeuNote")
     */
    private $userReview;

    /**
     * @var \JDJ\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="JDJ\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @var \JDJ\JeuBundle\Entity\Jeu
     *
     * @ORM\ManyToOne(targetEntity="JDJ\JeuBundle\Entity\Jeu", inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $jeu;

    /**
     * @var \JDJ\UserReviewBundle\Entity\Note
     *
     * @ORM\ManyToOne(targetEntity="Note")
     * @ORM\JoinColumn(nullable=false)
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
