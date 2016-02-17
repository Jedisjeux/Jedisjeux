<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 22/03/15
 * Time: 21:56
 */

namespace JDJ\CoreBundle\Entity;

use AppBundle\Entity\GameReview;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JDJ\CommentBundle\Entity\Comment;
use JDJ\UserReviewBundle\Entity\UserReview;


/**
 * Class Like
 * @package JDJ\CoreBundle\Entity
 *
 * @ORM\Entity(repositoryClass="JDJ\CoreBundle\Repository\LikeRepository")
 * @ORM\Table(name="jdj_like",uniqueConstraints={@ORM\UniqueConstraint(name="unique_like", columns={"createdBy_id", "gameReview_id"})})
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
     * @var GameReview
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GameReview", inversedBy="likes")
     */
    private $gameReview;

    /**
     * @var Comment
     *
     * @ORM\ManyToOne(targetEntity="JDJ\CommentBundle\Entity\Comment", inversedBy="likes")
     */
    private $comment;

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
        return (bool) $this->like;
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
     * @return GameReview
     */
    public function getGameReview()
    {
        return $this->gameReview;
    }

    /**
     * @param GameReview $gameReview
     *
     * @return $this
     */
    public function setGameReview($gameReview)
    {
        $this->gameReview = $gameReview;

        return $this;
    }

    /**
     * @return Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }


} 