<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 22/03/15
 * Time: 21:56
 */

namespace JDJ\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Class Like
 * @package JDJ\CoreBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_like")
 */
class Like
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
     * @var bool
     *
     * @ORM\Column(type="boolean", name="is_like")
     */
    private $like;

    /**
     * @var \JDJ\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="JDJ\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="JDJ\UserReviewBundle\Entity\UserReview", inversedBy="likes")
     */
    private $userReview;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isLike()
    {
        return $this->like;
    }

    /**
     * @param boolean $like
     * @return $this
     */
    public function setLike($like)
    {
        $this->like = $like;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \JDJ\UserBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param \JDJ\UserBundle\Entity\User $createdBy
     * @return $this
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserReview()
    {
        return $this->userReview;
    }

    /**
     * @param mixed $userReview
     * @return $this
     */
    public function setUserReview($userReview)
    {
        $this->userReview = $userReview;

        return $this;
    }



} 