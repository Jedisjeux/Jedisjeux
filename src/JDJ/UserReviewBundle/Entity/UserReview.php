<?php

namespace JDJ\UserReviewBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JDJ\CoreBundle\Entity\Like;

/**
 * UserReview
 *
 * @ORM\Entity(repositoryClass="JDJ\UserReviewBundle\Entity\UserReviewRepository")
 * @ORM\Table(name="jdj_user_review")
 */
class UserReview
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
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $body;

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
     * @var \JDJ\UserReviewBundle\Entity\JeuNote
     *
     * @ORM\OneToOne(targetEntity="JeuNote", inversedBy="userReview" , cascade={"persist", "merge"})
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $jeuNote;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="JDJ\CoreBundle\Entity\Like", mappedBy="userReview")
     */
    private $likes;


    public function __construct()
    {
        $this->likes = new ArrayCollection();
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

    /**
     * @return ArrayCollection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param ArrayCollection $likes
     * @return $this
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    public function getNbLikes()
    {
        $nb = 0;
        /** @var Like $like */
        foreach ($this->getLikes() as $like) {
            if ($like->isLike()) {
                $nb ++;
            }
        }
        return $nb;
    }

    public function getNbUnlikes()
    {
        $nb = 0;
        /** @var Like $like */
        foreach ($this->getLikes() as $like) {
            if (false === $like->isLike()) {
                $nb ++;
            }
        }
        return $nb;
    }

}
